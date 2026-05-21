<?php

declare(strict_types=1);

namespace Schnell\Http\FQL\Ast\Node\Literal;

use Override;
use Schnell\Http\FQL\Ast\AstInterface;
use Schnell\Http\FQL\Ast\Node\NodeTypes as AstNodeTypes;
use Schnell\Http\FQL\Ast\Node\PairedAwareNodeInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(AstNodeTypes::class);
// phpcs:enable

/**
 * @psalm-api
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class KeyValue implements PairedAwareNodeInterface
{
    /**
     * @var string|null
     */
    private ?string $key;

    /**
     * @var mixed
     */
    private mixed $value;

    /**
     * @psalm-api
     *
     * @param string|null $key
     * @param mixed $value
     * @return static
     */
    public function __construct(?string $key = null, mixed $value = null)
    {
        $this->setKey($key);
        $this->setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setKey(?string $key): void
    {
        $this->key = $key;
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
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getType(): int
    {
        return AstNodeTypes::KEY_VALUE;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getInvokable(AstInterface $ast): ?array
    {
        return null;
    }
}
