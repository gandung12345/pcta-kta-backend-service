<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Node\Sfinal ymbol;

use Schnell\Http\FQL\Node\NodeTypes;
use Schnell\Http\FQL\Node\SymbolNodeInterface;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class OpenSquareBrace implements SymbolNodeInterface
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getType(): int
    {
        return NodeTypes::OPEN_SQUARE_BRACE;
    }
}
