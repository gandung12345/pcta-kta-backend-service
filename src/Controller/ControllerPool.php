<?php

declare(strict_types=1);

namespace Schnell\Controller;

use Override;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use SplObjectStorage;
use Schnell\ContainerInterface;
use Schnell\Attribute\Route;
use Schnell\Exception\ControllerPoolException;
use Schnell\Config\ConfigInterface;

use function array_map;
use function class_exists;
use function basename;
use function glob;
use function preg_match;
use function sprintf;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(ReflectionAttribute::class);
class_exists(ReflectionClass::class);
class_exists(ReflectionMethod::class);
class_exists(SplObjectStorage::class);
class_exists(Route::class);
class_exists(ControllerPoolException::class);
// phpcs:enable

/**
 * @psalm-api
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class ControllerPool implements ControllerPoolInterface
{
    /**
     * @var \Schnell\ContainerInterface
     */
    private $container;

    /**
     * @var \SplObjectStorage
     */
    private $pool;

    /**
     * @var \Schnell\Config\ConfigInterface
     */
    private $config;

    /**
     * @psalm-api
     *
     * @param \Schnell\ContainerInterface $container
     * @param \Schnell\Config\ConfigInterface $config
     * @param \SplObjectStorage $pool
     * @return static
     */
    public function __construct(
        ContainerInterface $container,
        ConfigInterface $config,
        SplObjectStorage $pool
    ) {
        $this->setContainer($container);
        $this->setConfig($config);
        $this->setPool($pool);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getPool(): SplObjectStorage
    {
        return $this->pool;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getPoolAt(object $key)
    {
        return $this->pool[$key];
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setPool(SplObjectStorage $pool): void
    {
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function addPoolAt(object $key, $value): void
    {
        $this->pool[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setConfig(ConfigInterface $config): void
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function collect(): void
    {
        $recGlobFn = function (string $pattern, int $flags = 0) use (&$recGlobFn): array {
            $files = glob($pattern, $flags);
            $result = [];

            foreach ($files as $file) {
                if (is_dir($file)) {
                    clearstatcache();

                    $result = array_merge(
                        $result,
                        $recGlobFn(sprintf("%s%s*", $file, DIRECTORY_SEPARATOR), $flags)
                    );

                    continue;
                }

                $result[] = $file;
            }

            return $result;
        };

        $globPattern = sprintf(
            "%s%s*",
            rtrim($this->getConfig()->get('controller.path'), DIRECTORY_SEPARATOR),
            DIRECTORY_SEPARATOR,
        );

        $controllerFiles = $recGlobFn($globPattern);

        if (false === $controllerFiles) {
            return;
        }

        $controllerNs = rtrim($this->getConfig()->get('controller.namespace'), '\\');
        $controllerClassFn = function (string $el) use ($controllerNs): string {
            $ret = preg_match(
                '/(.*(Controller))(?:.php)/',
                basename($el),
                $matches
            );

            if (false === $ret || 0 === $ret) {
                return '';
            }

            $fqcn = str_replace(
                $this->getConfig()->get('controller.path'),
                $this->getConfig()->get('controller.namespace'),
                dirname($el)
            );

            return strtr($fqcn, DIRECTORY_SEPARATOR, '\\') . '\\' . $matches[1];
        };

        $controllerClasses = array_map($controllerClassFn, $controllerFiles);

        foreach ($controllerClasses as $controllerClass) {
            if (
                $controllerClass === $controllerNs . '\\' ||
                $controllerClass === ''
            ) {
                continue;
            }

            $this->resolveControllerClass($controllerClass);
        }
    }

    /**
     * @param string $name
     * @return void
     */
    private function resolveControllerClass(string $name): void
    {
        try {
            $routeGroup = $this->resolveContextualRouteGroup($name);
        } catch (ControllerPoolException $e) {
            throw $e;
        }

        /** @psalm-suppress ArgumentTypeCoercion */
        $reflection = new ReflectionClass($name);
        $ctrlInstance = $reflection->newInstance(
            $this->getContainer(),
            $this->getConfig()
        );

        $classMethods = array_filter(
            $reflection->getMethods(),
            function (ReflectionMethod $method) use ($reflection) {
                return $method->getDeclaringClass()->getName() === $reflection->getName();
            }
        );

        if (sizeof($classMethods) === 0) {
            return;
        }

        $routeAttr = null;

        foreach ($classMethods as $classMethod) {
            $attributes = $classMethod->getAttributes();

            if (sizeof($attributes) === 0) {
                continue;
            }

            $attrObjs = [
                'controller' => $ctrlInstance,
                'method' => $classMethod->getName()
            ];

            foreach ($attributes as $attribute) {
                if (
                    false !== stripos($attribute->getName(), 'OpenApi') ||
                    false !== stripos($attribute->getName(), 'Override')
                ) {
                    continue;
                }

                if ($attribute->getName() === Route::class) {
                    $routeAttr = $attribute->newInstance();
                    $routeAttr->setUrl(sprintf('%s%s', $routeGroup, $routeAttr->getUrl()));
                    $attrObjs[$routeAttr->getIdentifier()] = $routeAttr;
                    continue;
                }

                $attrObj = $attribute->newInstance();
                $attrObjs[$attrObj->getIdentifier()] = $attrObj;
            }

            /** @psalm-suppress PossiblyNullArgument */
            if (null !== $routeAttr) {
                $this->addPoolAt($routeAttr, $attrObjs);
            }
        }
    }

    /**
     * @internal
     *
     * @param string $className
     * @return string
     *
     * @throws \Schnell\Exception\ControllerPoolException
     */
    private function resolveContextualRouteGroup(string $className): string
    {
        try {
            $reflection = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            throw new ControllerPoolException(
                sprintf(
                    'Unable to reflect class (%s, reason: %s)',
                    $className,
                    $e->getMessage()
                )
            );
        }

        $attributes = $reflection->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

        if (sizeof($attributes) !== 1) {
            return '';
        }

        return $attributes[0]->newInstance()->getUrl();
    }
}
