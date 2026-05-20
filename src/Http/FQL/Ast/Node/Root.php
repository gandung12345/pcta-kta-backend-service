<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Ast\Node;

use Schnell\Http\FQL\Ast\AstInterface;

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
    #[\Override]
    public function getType(): int
    {
        return NodeTypes::ROOT;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getInvokable(AstInterface $ast): ?array
    {
        return [$ast->getQueryBuilder(), 'andWhere'];
    }
}
