<?php

declare(strict_types=1);

namespace Schnell\Entity;

use Override;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
abstract class AbstractEntity implements EntityInterface
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function getQueryBuilderAlias(): string;

    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function getCanonicalTableName(): string;

    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function getDqlName(): string;
}
