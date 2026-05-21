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
class Root extends AbstractNode
{
    /**
     * @psalm-api
     *
     * @return static
     */
    public function __construct()
    {
        $this->type = NodeTypes::ROOT;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getName(): string
    {
        return '(root)';
    }
}
