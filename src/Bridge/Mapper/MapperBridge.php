<?php

declare(strict_types=1);

namespace Schnell\Bridge\Mapper;

use Override;
use Doctrine\ORM\EntityManagerInterface;
use Schnell\ContainerInterface;
use Schnell\Bridge\AbstractBridge;
use Schnell\Config\ConfigInterface;
use Schnell\Exception\ExtensionException;
use Schnell\Mapper\Mapper;
use Schnell\Mapper\MapperInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// @codeCoverageIgnoreStart
// phpcs:disable
class_exists(Override::class);
class_exists(AbstractBridge::class);
class_exists(ExtensionException::class);
class_exists(Mapper::class);
// phpcs:enable
// @codeCoverageIgnoreEnd

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class MapperBridge extends AbstractBridge
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function load(): void
    {
        $container = $this->getContainer();

        /** @psalm-suppress PossiblyNullReference */
        if (false === $container->has(EntityManagerInterface::class)) {
            throw new ExtensionException(
                sprintf(
                    "Object instance with type '%s' not found.",
                    EntityManagerInterface::class
                )
            );
        }

        /** @psalm-suppress PossiblyNullReference */
        $container->set(
            MapperInterface::class,
            function (ContainerInterface $container, ConfigInterface $config): MapperInterface {
                return new Mapper($container->get(EntityManagerInterface::class));
            }
        );

        /** @psalm-suppress PossiblyNullReference */
        $container->alias(MapperInterface::class, $this->getAlias());
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getAlias(): string
    {
        return 'mapper';
    }
}
