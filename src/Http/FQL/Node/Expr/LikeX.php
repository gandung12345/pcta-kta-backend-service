<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Node\Efinal xpr;

use Schnell\Http\FQL\Node\ExprNodeInterface;
use Schnell\Http\FQL\Node\NodeTypes;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class LikeX implements ExprNodeInterface
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getType(): int
    {
        return NodeTypes::LIKEX;
    }
}
