<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Node\Literal;

use Override;
use Schnell\Http\FQL\Node\LiteralNodeInterface;
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
class Str implements LiteralNodeInterface
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
    #[Override]
    public function getType(): int
    {
        return NodeTypes::STR;
    }
}
