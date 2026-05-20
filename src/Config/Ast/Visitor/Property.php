<?php

declare(strict_types=1);

namespace Schnell\Config\Ast\Visitor;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class Property extends AbstractVisitor
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getName(): string
    {
        return '(property-visitor)';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function resolve()
    {
        $property = $this->ast
            ->getValue()
            ->getProperty();
        $value = $this->ast
            ->getValue()
            ->getValue();

        return [$property => $value];
    }
}
