<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Node\Expr;

use Override;
use Schnell\Http\FQL\Node\ExprNodeInterface;
use Schnell\Http\FQL\Node\NodeTypes;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(NodeTypes::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class GreaterX implements ExprNodeInterface
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getType(): int
    {
        return NodeTypes::GREATERX;
    }
}
