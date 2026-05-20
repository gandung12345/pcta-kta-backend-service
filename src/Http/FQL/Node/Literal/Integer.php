<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Node\Lfinal iteral;

use Schnell\Http\FQL\Node\LiteralNodeInterface;
use Schnell\Http\FQL\Node\NodeTypes;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class Integer implements LiteralNodeInterface
{
    use LiteralTrait;

    /**
     * @psalm-api
     *
     * @param mixed $value
     * @return static
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getType(): int
    {
        return NodeTypes::INTEGER;
    }
}
