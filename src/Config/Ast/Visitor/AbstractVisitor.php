<?php

declare(strict_types=1);

namespace Schnell\Config\Ast\Visitor;

use Override;
use Schnell\Config\Ast\AstInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
abstract class AbstractVisitor implements VisitorInterface
{
    /**
     * @var \Schnell\Config\Ast\AstInterface
     */
    protected $ast;

    /**
     * @psalm-api
     *
     * @param \Schnell\Config\Ast\AstInterface $ast
     * @return static
     */
    public function __construct(AstInterface $ast)
    {
        $this->ast = $ast;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function getName(): string;

    /**
     * {@inheritdoc}
     */
    #[Override]
    abstract public function resolve();
}
