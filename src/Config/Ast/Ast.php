<?php

declare(strict_types=1);

namespace Schnell\Config\Ast;

use Override;
use Schnell\Config\Ast\Visitor\VisitorInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @psalm-api
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class Ast implements AstInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var \Schnell\Config\Ast\Visitor\VisitorInterface|null
     */
    private ?VisitorInterface $visitor;

    /**
     * @readonly
     * @psalm-allow-private-mutation
     *
     * @var array
     */
    private array $childs;

    /**
     * @psalm-api
     *
     * @param mixed $value
     * @param \Schnell\Config\Ast\Visitor\VisitorInterface|null $visitor
     * @return static
     */
    public function __construct($value, ?VisitorInterface $visitor)
    {
        $this->initialize($value, $visitor);
    }

    /**
     * @param mixed $value
     * @param \Schnell\Config\Ast\Visitor\VisitorInterface|null $visitor
     */
    private function initialize($value, ?VisitorInterface $visitor): void
    {
        $this->setValue($value);
        $this->setVisitor($visitor);
        $this->setChilds([]);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getVisitor(): VisitorInterface|null
    {
        return $this->visitor;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setVisitor(VisitorInterface|null $visitor): void
    {
        $this->visitor = $visitor;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getChilds(): array
    {
        return $this->childs;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setChilds(array $childs): void
    {
        $this->childs = $childs;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function addChild(AstInterface $child): void
    {
        $this->childs[] = $child;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getChildAt(int $index): AstInterface|null
    {
        if (!isset($this->childs[$index])) {
            return null;
        }

        return $this->childs[$index];
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getLastChild(): AstInterface|null
    {
        return $this->getChildAt(sizeof($this->childs) - 1);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function visit()
    {
        if ($this->getVisitor() === null) {
            return null;
        }

        /** @psalm-suppress PossiblyNullReference */
        return $this->getVisitor()->resolve();
    }
}
