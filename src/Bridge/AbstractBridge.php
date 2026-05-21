<?php

declare(strict_types=1);

namespace Schnell\Bridge;

use Override;
use Schnell\ContainerInterface;
use Schnell\Config\ConfigInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
abstract class AbstractBridge implements BridgeInterface
{
    /**
     * @readonly
     * @psalm-allow-private-mutation
     *
     * @var \Schnell\Config\ConfigInterface|null
     */
    private ?ConfigInterface $config;

    /**
     * @var \Schnell\ContainerInterface|null
     */
    private ?ContainerInterface $container;

    /**
     * @readonly
     * @psalm-allow-private-mutation
     *
     * @var string
     */
    private ?string $basePath;

    /**
     * @param \Schnell\Config\ConfigInterface|null $config
     * @param \Schnell\ContainerInterface|null $container
     * @param string|null $basePath
     * @return static
     *
     * @psalm-api
     * @psalm-param \Schnell\Config\ConfigInterface|null $config
     * @psalm-param \Schnell\ContainerInterface|null $container
     * @psalm-ignore-nullable-return
     */
    public function __construct(
        ?ConfigInterface $config = null,
        ?ContainerInterface $container = null,
        ?string $basePath = null,
    ) {
        $this->config = $config;
        $this->container = $container;
        $this->basePath = $basePath;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getConfig(): ConfigInterface|null
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setConfig(ConfigInterface|null $config): void
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getContainer(): ContainerInterface|null
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setContainer(ContainerInterface|null $container): void
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getBasePath(): string|null
    {
        return $this->basePath;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setBasePath(string|null $path): void
    {
        $this->basePath = $path;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function load(): void;

    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function getAlias(): string;
}
