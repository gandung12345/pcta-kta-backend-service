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
final class Regex implements AttributeInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @psalm-api
     *
     * @param string $pattern
     * @return static
     */
    public function __construct(string $pattern)
    {
        $this->setPattern($pattern);
    }

    /**
     * @psalm-api
     *
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return void
     */
    public function setPattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getIdentifier(): string
    {
        return 'schema.regex';
    }
}
