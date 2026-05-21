<?php

declare(strict_types=1);

namespace Schnell\Bridge\Cache\Factory;

use Override;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(FilesystemAdapter::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class FilesystemCacheFactory extends AbstractCacheFactory
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function createCache(): CacheItemPoolInterface
    {
        return new FilesystemAdapter(
            $this->getConfig()->get('cache.namespace'),
            $this->getConfig()->get('cache.defaultLifetime'),
            $this->getConfig()->get('cache.directory')
        );
    }
}
