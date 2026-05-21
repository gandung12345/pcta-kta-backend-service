<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Ast\Node;

use Override;
use Schnell\Http\FQL\Ast\AstInterface;

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
class Root implements NodeInterface
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getType(): int
    {
        return NodeTypes::ROOT;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getInvokable(AstInterface $ast): ?array
    {
        return [$ast->getQueryBuilder(), 'andWhere'];
    }
}
