<?php

declare(strict_types=1);

namespace Schnell\Bridge\Cache\Factory;

use Override;
use Psr\Cache\CacheItemPoolInterface;
use Schnell\Bridge\Cache\CacheFactoryInterface;
use Schnell\Config\ConfigInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
abstract class AbstractCacheFactory implements CacheFactoryInterface
{
    use CacheExtensionTrait;

    /**
     * @var \Schnell\Config\ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param \Schnell\Config\ConfigInterface $config
     * @return static
     */
    public function __construct(ConfigInterface $config)
    {
        $this->setConfig($config);
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
    abstract public function createCache(): CacheItemPoolInterface;
}
