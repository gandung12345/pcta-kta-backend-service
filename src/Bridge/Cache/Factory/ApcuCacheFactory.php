<?php

declare(strict_types=1);

namespace Schnell\Bridge\Cache\Factory;

use Override;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(ApcuAdapter::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class ApcuCacheFactory extends AbstractCacheFactory
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function createCache(): CacheItemPoolInterface
    {
        $this->checkExtension('apcu');

        return new ApcuAdapter(
            $this->getConfig()->get('cache.namespace'),
            $this->getConfig()->get('cache.defaultLifetime')
        );
    }
}
