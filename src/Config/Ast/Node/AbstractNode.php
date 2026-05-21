<?php

declare(strict_types=1);

namespace Schnell\Config\Ast\Node;

use Override;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * @var int
     */
    protected $type;

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function getName(): string;
}
