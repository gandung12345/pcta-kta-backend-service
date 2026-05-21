<?php

declare(strict_types=1);

namespace Schnell\Bridge\Cache;

use Override;
use Schnell\Config\ConfigInterface;
use Schnell\Bridge\Cache\Exception\CacheFactoryException;
use Schnell\Bridge\Cache\Factory\ApcuCacheFactory;
use Schnell\Bridge\Cache\Factory\ArrayCacheFactory;
use Schnell\Bridge\Cache\Factory\FilesystemCacheFactory;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(CacheFactoryException::class);
class_exists(ApcuCacheFactory::class);
class_exists(ArrayCacheFactory::class);
class_exists(FilesystemCacheFactory::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class CacheFlyweightFactory implements CacheFlyweightFactoryInterface
{
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
    public function createFactory(string $cacheDriver): CacheFactoryInterface
    {
        return match ($cacheDriver) {
            'apcu' => new ApcuCacheFactory($this->getConfig()),
            'array' => new ArrayCacheFactory($this->getConfig()),
            'file' => new FilesystemCacheFactory($this->getConfig()),
            default => throw new CacheFactoryException(
                sprintf('Cache factory with identifier \'%s\' not found', $cacheDriver)
            )
        };
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
}
