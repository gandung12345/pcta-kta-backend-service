<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Ast\Node\Expr;

use Override;
use Schnell\Http\FQL\Ast\AstInterface;
use Schnell\Http\FQL\Ast\Node\ExprNodeInterface;
use Schnell\Http\FQL\Ast\Node\NodeTypes;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(NodeTypes::class);
// phpcs:enable

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class ExprLikeX implements ExprNodeInterface
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getOperator(): string
    {
        return 'LIKE';
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getType(): int
    {
        return NodeTypes::EXPR_LIKEX;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getInvokable(AstInterface $ast): ?array
    {
        return [$ast->getQueryBuilder()->expr(), 'like'];
    }
}
