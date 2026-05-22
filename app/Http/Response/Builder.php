<?php

declare(strict_types=1);

namespace Pcta\Api\Http\Response;

use Override;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class Builder implements BuilderInterface
{
    /**
     * @var array
     */
    private array $list = [];

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setPair(string $key, mixed $value): void
    {
        $this->list[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function withPair(string $key, mixed $value): BuilderInterface
    {
        $ret = clone $this;
        $ret->setPair($key, $value);
        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function build(): array
    {
        return $this->list;
    }
}
