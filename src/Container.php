<?php

declare(strict_types=1);

namespace Schnell;

use Closure;
use Override;
use ReflectionClass;
use ReflectionException;
use Schnell\Config\ConfigInterface;
use Schnell\Exception\ContainerException;
use Schnell\Exception\NotFoundException;

use function array_key_exists;
use function class_exists;
use function call_user_func;
use function is_string;
use function sprintf;

// help opcache.preload discover always-needed symbols
// @codeCoverageIgnoreStart
// phpcs:disable
class_exists(Closure::class);
class_exists(Override::class);
class_exists(ReflectionClass::class);
class_exists(ReflectionException::class);
class_exists(ContainerException::class);
class_exists(NotFoundException::class);
// phpcs:enable
// @codeCoverageIgnoreEnd

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private array $definitions = [];

    /**
     * @var array
     */
    private array $instances = [];

    /**
     * @var array
     */
    private array $aliases = [];

    /**
     * @var \Schnell\Config\ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @var \Schnell\Config\ConfigInterface $config
     * @var array $definitions
     */
    public function __construct(ConfigInterface $config, array $definitions = [])
    {
        $this->setConfig($config);
        $this->setMultiple($definitions);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function get(string $id)
    {
        if (array_key_exists($id, $this->aliases)) {
            return $this->get($this->aliases[$id]);
        }

        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        $this->instances[$id] = $this->createFromDefinition($id);
        return $this->instances[$id];
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function set(string $id, $definition): void
    {
        if (array_key_exists($id, $this->instances)) {
            unset($this->instances[$id]);
        }

        $this->definitions[$id] = $definition;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setMultiple(array $definitions): void
    {
        foreach ($definitions as $id => $definition) {
            $this->set($id, $definition);
        }
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function alias(string $className, string $alias): void
    {
        if (array_key_exists($alias, $this->aliases)) {
            unset($this->aliases[$alias]);
        }

        $this->aliases[$alias] = $className;
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
     * @internal
     *
     * @param string $id
     * @return object
     */
    private function autowire(string $id): object
    {
        try {
            $reflection = new ReflectionClass($id);
        } catch (ReflectionException $e) {
            throw new ContainerException(sprintf('Unable to create object \'%s\'.', $id));
        }

        if (($constructor = $reflection->getConstructor()) === null) {
            return $reflection->newInstance();
        }

        $args = [];

        foreach ($constructor->getParameters() as $parameter) {
            if ($type = $parameter->getType()) {
                $typeName = $type->getName();

                if (!$type->isBuiltin() && ($this->has($typeName) || $this->isClassName($typeName))) {
                    $args[] = $this->get($typeName);
                    continue;
                }

                if ($typeName === 'array' && $type->isBuiltin() && !$parameter->isDefaultValueAvailable()) {
                    $arguments[] = [];
                    continue;
                }
            }

            if ($parameter->isDefaultValueAvailable()) {
                try {
                    $args[] = $parameter->getDefaultValue();
                    continue;
                } catch (ReflectionException $e) {
                    throw new ContainerException(sprintf(
                        'Unable to create object \'%s\'. Unable to get default value of constructor parameter: \'%s\'.',
                        $reflection->getName(),
                        $parameter->getName()
                    ));
                }
            }

            throw new ContainerException(sprintf(
                'Unable to create object \'%s\'. Unable to process a constructor parameter: \'%s\'.',
                $reflection->getName(),
                $parameter->getName()
            ));
        }

        return $reflection->newInstanceArgs($args);
    }

    /**
     * @internal
     *
     * @param string $id
     * @return mixed
     */
    private function createFromDefinition(string $id): mixed
    {
        if (!$this->has($id)) {
            if ($this->isClassName($id)) {
                return $this->autowire($id);
            }

            throw new NotFoundException('Definition with id \'%s\' not found.', $id);
        }

        if ($this->isClassName($this->definitions[$id])) {
            return $this->createFromDefinition($this->definitions[$id]);
        }

        if ($this->definitions[$id] instanceof Closure) {
            return call_user_func($this->definitions[$id], $this, $this->getConfig());
        }

        return $this->definitions[$id];
    }

    /**
     * @internal
     *
     * @param mixed $id
     * @return bool
     */
    private function isClassName(mixed $id): bool
    {
        return is_string($id) && class_exists($id);
    }
}
