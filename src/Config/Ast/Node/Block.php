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
class Block extends AbstractNode
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @psalm-api
     *
     * @param mixed $value
     * @return static
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->type  = NodeTypes::BLOCK;
    }

    /**
     * @psalm-api
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getName(): string
    {
        return '(block)';
    }
}
