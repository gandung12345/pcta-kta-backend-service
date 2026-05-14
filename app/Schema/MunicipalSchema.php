<?php

declare(strict_types=1);

namespace Pcta\Api\Schema;

use Schnell\Attribute\Schema\Json;
use Schnell\Attribute\Schema\Rule;
use Schnell\Schema\AbstractSchema;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Json::class);
class_exists(Rule::class);
class_exists(AbstractSchema::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class MunicipalSchema extends AbstractSchema
{
    use SchemaTrait;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'formalIdentifier')]
    private ?string $formalIdentifier;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'formalName')]
    private ?string $formalName;

    /**
     * @param string|null $formalIdentifier
     * @param string|null $formalName
     * @return static
     */
    public function __construct(?string $formalIdentifier, ?string $formalName)
    {
        $this->formalIdentifier = $formalIdentifier;
        $this->formalName = $formalName;
    }

    /**
     * @return string|null
     */
    public function getFormalIdentifier(): ?string
    {
        return $this->formalIdentifier;
    }

    /**
     * @param string|null $formalIdentifier
     * @return void
     */
    public function setFormalIdentifier(?string $formalIdentifier): void
    {
        $this->formalIdentifier = $formalIdentifier;
    }

    /**
     * @return string|null
     */
    public function getFormalName(): ?string
    {
        return $this->formalName;
    }

    /**
     * @param string|null $formalName
     * @return void
     */
    public function setFormalName(?string $formalName): void
    {
        $this->formalName = $formalName;
    }
}
