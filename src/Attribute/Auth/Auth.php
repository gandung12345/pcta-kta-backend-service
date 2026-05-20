<?php

declare(strict_types=1);

namespace Schnell\Attribute\Auth;

use Attribute;
use Override;
use Schnell\Attribute\AttributeInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// @codeCoverageIgnoreStart
// phpcs:disable
class_exists(Attribute::class);
class_exists(Override::class);
// phpcs:enable
// @codeCoverageIgnoreEnd

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Auth implements AttributeInterface
{
    /**
     * @var array|null
     */
    private ?array $role;

    /**
     * @param array|null $role
     */
    public function __construct(?array $role)
    {
        $this->setRole($role);
    }

    /**
     * @return array|null
     */
    public function getRole(): ?array
    {
        return $this->role;
    }

    /**
     * @param array|null $role
     * @return void
     */
    public function setRole(?array $role): void
    {
        $this->role = $role;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getIdentifier(): string
    {
        return 'auth.auth';
    }
}
