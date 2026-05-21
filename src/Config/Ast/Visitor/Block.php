<?php

declare(strict_types=1);

namespace Schnell\Config\Ast\Visitor;

use Override;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class Block extends AbstractVisitor
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getName(): string
    {
        return '(block-visitor)';
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function resolve()
    {
        return $this->ast->getValue()->getValue();
    }
}
