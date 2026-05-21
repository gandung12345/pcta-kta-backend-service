<?php

declare(strict_types=1);

namespace Schnell\Bridge\Cache\Factory;

use Override;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(ArrayAdapter::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class ArrayCacheFactory extends AbstractCacheFactory
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function createCache(): CacheItemPoolInterface
    {
        return new ArrayAdapter(
            $this->getConfig()->get('cache.defaultLifetime'),
            true,
            $this->getConfig()->get('cache.maxStorageLifetime'),
            $this->getConfig()->get('cache.maxItems')
        );
    }
}
