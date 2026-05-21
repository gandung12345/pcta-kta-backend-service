<?php

declare(strict_types=1);

namespace Schnell\Bridge\SlimCacheProvider;

use Override;
use Schnell\Bridge\AbstractBridge;
use Slim\HttpCache\CacheProvider;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(AbstractBridge::class);
class_exists(CacheProvider::class);
// phpcs:enable

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class SlimCacheProviderBridge extends AbstractBridge
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function load(): void
    {
        $container = $this->getContainer();

        /** @psalm-suppress PossiblyNullReference */
        $container->set(CacheProvider::class, new CacheProvider());
        /** @psalm-suppress PossiblyNullReference */
        $container->alias(CacheProvider::class, $this->getAlias());
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getAlias(): string
    {
        return 'slim-cache-provider';
    }
}
