<?php

declare(strict_types=1);

namespace Schnell;

use Override;
use Schnell\ContainerInterface;
use Schnell\Bridge\BridgeInterface;
use Schnell\Config\ConfigInterface;
use Schnell\Controller\ControllerResolverInterface;
use Psr\Http\Message\RequestInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
final class Kernel implements KernelInterface
{
    /**
     * @var \Schnell\Config\ConfigInterface
     */
    private $config;

    /**
     * @var \Schnell\ContainerInterface
     */
    private $container;

    /**
     * @var \Schnell\Controller\ControllerResolverInterface
     */
    private $controllerResolver;

    /**
     * @readonly
     * @psalm-allow-private-mutation
     *
     * @var array
     */
    private $extensions = [];

    /**
     * @psalm-api
     *
     * @param \Schnell\Config\ConfigInterface $config
     * @param \Schnell\ContainerInterface $container
     * @param \Schnell\Controller\ControllerResolverInterface $resolver
     * @return static
     */
    public function __construct(
        ConfigInterface $config,
        ContainerInterface $container,
        ControllerResolverInterface $resolver
    ) {
        $this->setConfig($config);
        $this->setContainer($container);
        $this->setControllerResolver($resolver);
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
    public function getControllerResolver(): ControllerResolverInterface
    {
        return $this->controllerResolver;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setControllerResolver(
        ControllerResolverInterface $controllerResolver
    ): void {
        $this->controllerResolver = $controllerResolver;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function handle(RequestInterface $request): void
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $this->getControllerResolver()->run($request);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function addExtension(
        BridgeInterface $extension,
        string|null $basePath = null
    ): KernelInterface {
        $extension->setConfig($this->getConfig());
        $extension->setContainer($this->getContainer());
        $extension->setBasePath($basePath);

        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function load(): void
    {
        foreach ($this->extensions as $extension) {
            $extension->load();
        }
    }
}
