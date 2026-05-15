<?php

declare(strict_types=1);

namespace Pcta\Api\Schema;

use Schnell\Attribute\Schema\Json;
use Schnell\Attribute\Schema\Regex;
use Schnell\Attribute\Schema\Rule;
use Schnell\Attribute\Schema\TransformedClassType;
use Schnell\Decorator\Stringified\DateTimeDecorator;
use Schnell\Schema\AbstractSchema;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Json::class);
class_exists(Regex::class);
class_exists(Rule::class);
class_exists(TransformedClassType::class);
class_exists(DateTimeDecorator::class);
class_exists(AbstractSchema::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class MemberSchema extends AbstractSchema
{
    use SchemaTrait;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'identityNumber')]
    #[Regex(pattern: self::IDENTITY_NUMBER_PATTERN)]
    private ?string $identityNumber;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'postalCode')]
    #[Regex(pattern: self::POSTAL_CODE_PATTERN)]
    private ?string $postalCode;

    /**
     * @var string|null
     */
    #[Rule(reuqired: true)]
    #[Json(name: 'name')]
    private ?string $name;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'phoneNumber')]
    #[Regex(pattern: self::PHONE_NUMBER_PATTERN)]
    private ?string $phoneNumber;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'address')]
    private ?string $address;

    /**
     * @var \Schnell\Decorator\Stringified\DateTimeDecorator|null
     */
    #[Rule(required: true)]
    #[Json(name: 'dateOfBirth')]
    #[Regex(pattern: self::ISO8601_DATE_PATTERN)]
    #[TransformedClassType(name: DateTimeDecorator::class)]
    private ?DateTimeDecorator $dateOfBirth;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'placeOfBirth')]
    private ?string $placeOfBirth;

    /**
     * @var \Schnell\Decorator\Stringified\DateTimeDecorator|null
     */
    #[Rule(required: false)]
    #[Json(name: 'workDate')]
    #[Regex(pattern: self::ISO8601_DATE_PATTERN)]
    #[TransformedClassType(name: DateTimeDecorator::class)]
    private ?DateTimeDecorator $workDate;

    /**
     * @var string|null
     */
    #[Rule(required: false)]
    #[Json(name: 'workPlace')]
    #[Regex(pattern: self::ISO8601_DATE_PATTERN)]
    #[TransformedClassType(name: DateTimeDecorator::class)]
    private ?string $workPlace;

    /**
     * @param string|null $identityNumber
     * @param string|null $postalCode
     * @param string|null $name
     * @param string|null $phoneNumber
     * @param string|null $address
     * @param \Schnell\Decorator\Stringified\DateTimeDecorator|null $dateOfBirth
     * @param string|null $placeOfBirth
     * @param \Schnell\Decorator\Stringified\DateTimeDecorator|null $workDate
     * @param string|null $workPlace
     * @return static
     */
    public function __construct(
        ?string $identityNumber,
        ?string $postalCode,
        ?string $name,
        ?string $phoneNumber,
        ?string $address,
        ?DateTimeDecorator $dateOfBirth,
        ?string $placeOfBirth,
        ?DateTimeDecorator $workDate,
        ?string $workPlace
    ) {
        $this->identityNumber = $identityNumber;
        $this->postalCode = $postalCode;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->dateOfBirth = $dateOfBirth;
        $this->placeOfBirth = $placeOfBirth;
        $this->workDate = $workDate;
        $this->workPlace = $workPlace;
    }

    /**
     * @return string|null
     */
    public function getIdentityNumber(): ?string
    {
        return $this->identityNumber;
    }

    /**
     * @param string|null $identityNumber
     * @return void
     */
    public function setIdentityNumber(?string $identityNumber): void
    {
        $this->identityNumber = $identityNumber;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param string|null $postalCode
     * @return void
     */
    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     * @return void
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return void
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return \Schnell\Decorator\Stringified\DateTimeDecorator|null
     */
    public function getDateOfBirth(): ?DateTimeDecorator
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \Schnell\Decorator\Stringified\DateTimeDecorator|null $dateOfBirth
     * @return void
     */
    public function setDateOfBirth(?DateTimeDecorator $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return string|null
     */
    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    /**
     * @param string|null $placeOfBirth
     * @return void
     */
    public function setPlaceOfBirth(?string $placeOfBirth): void
    {
        $this->placeOfBirth = $placeOfBirth;
    }

    /**
     * @return \Schnell\Decorator\Stringified\DateTimeDecorator|null
     */
    public function getWorkDate(): ?DateTimeDecorator
    {
        return $this->workDate;
    }

    /**
     * @param \Schnell\Decorator\Stringified\DateTimeDecorator|null $workDate
     * @return void
     */
    public function setWorkDate(?DateTimeDecorator $workDate): void
    {
        $this->workDate = $workDate;
    }

    /**
     * @return string|null
     */
    public function getWorkPlace(): ?string
    {
        return $this->workPlace;
    }

    /**
     * @param string|null $workPlace
     * @return void
     */
    public function setWorkPlace(?string $workPlace): void
    {
        $this->workPlace = $workPlace;
    }
}
