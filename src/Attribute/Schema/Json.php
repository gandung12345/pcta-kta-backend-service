<?php

declare(strict_types=1);

namespace Schnell\Attribute\Schema;

use Attribute;
use Override;
use Schnell\Attribute\AttributeInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Attribute::class);
class_exists(Override::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_PROPERTY)]
final class Json implements AttributeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @psalm-api
     *
     * @param string $name
     * @return static
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @psalm-api
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getIdentifier(): string
    {
        return 'schema.json';
    }
}
