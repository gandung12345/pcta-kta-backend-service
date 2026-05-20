<?php

declare(strict_types=1);

namespace Schnell\Entity;

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
abstract class AbstractEntity implements EntityInterface
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    abstract public function getQueryBuilderAlias(): string;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    abstract public function getCanonicalTableName(): string;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    abstract public function getDqlName(): string;
}
