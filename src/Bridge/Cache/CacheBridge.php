<?php

declare(strict_types=1);

namespace Schnell\Bridge\Cache;

use Override;
use Schnell\Bridge\AbstractBridge;
use Psr\Cache\CacheItemPoolInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(AbstractBridge::class);
// phpcs:enable

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class CacheBridge extends AbstractBridge
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function load(): void
    {
        $flyweight = new CacheFlyweightFactory($this->getConfig());
        $factory = $flyweight->createFactory($this->getConfig()->get('cache.driver'));

        $this->getContainer()->set(CacheItemPoolInterface::class, $factory->createCache());
        $this->getContainer()->alias(CacheItemPoolInterface::class, $this->getAlias());
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getAlias(): string
    {
        return 'cache';
    }
}
