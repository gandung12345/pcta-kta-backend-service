<?php

declare(strict_types=1);

namespace Pcta\Api\Schema;

use OpenApi\Attributes as OpenApi;
use Pcta\Api\Type\Religion as ReligionType;
use Pcta\Api\Type\Role as RoleType;
use Pcta\Api\Type\Sex as SexType;
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
#[OpenApi\Schema(
    schema: 'MemberSchema',
    type: 'object'
)]
class MemberSchema extends AbstractSchema
{
    use SchemaTrait;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'identityNumber')]
    #[Regex(pattern: self::IDENTITY_NUMBER_PATTERN)]
    #[OpenApi\Property(
        property: 'identityNumber',
        type: 'string',
        description: 'Member schema identity number'
    )]
    private ?string $identityNumber;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'postalCode')]
    #[Regex(pattern: self::POSTAL_CODE_PATTERN)]
    #[OpenApi\Property(
        property: 'postalCode',
        type: 'string',
        description: 'Member schema postal code'
    )]
    private ?string $postalCode;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'name')]
    #[OpenApi\Property(
        property: 'name',
        type: 'string',
        description: 'Member schema name'
    )]
    private ?string $name;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'phoneNumber')]
    #[Regex(pattern: self::PHONE_NUMBER_PATTERN)]
    #[OpenApi\Property(
        property: 'phoneNumber',
        type: 'string',
        description: 'Member schema phone number'
    )]
    private ?string $phoneNumber;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'address')]
    #[OpenApi\Property(
        property: 'address',
        type: 'string',
        description: 'Member schema address'
    )]
    private ?string $address;

    #[Rule(required: true)]
    #[Json(name: 'sex')]
    #[OpenApi\Property(
        property: 'sex',
        type: 'string',
        description: 'Member schema sex type'
    )]
    private ?SexType $sex;

    #[Rule(required: true)]
    #[Json(name: 'religion')]
    #[OpenApi\Property(
        property: 'religion',
        type: 'string',
        description: 'Member schema religion type'
    )]
    private ?ReligionType $religion;

    #[Rule(required: true)]
    #[Json(name: 'role')]
    #[OpenApi\Property(
        property: 'role',
        type: 'string',
        description: 'Member schema role type'
    )]
    private ?RoleType $role;

    /**
     * @var \Schnell\Decorator\Stringified\DateTimeDecorator|null
     */
    #[Rule(required: true)]
    #[Json(name: 'dateOfBirth')]
    #[Regex(pattern: self::ISO8601_DATE_PATTERN)]
    #[TransformedClassType(name: DateTimeDecorator::class)]
    #[OpenApi\Property(
        property: 'dateOfBirth',
        type: 'string',
        format: 'date',
        description: 'Member schema date of birth'
    )]
    private ?DateTimeDecorator $dateOfBirth;

    /**
     * @var string|null
     */
    #[Rule(required: true)]
    #[Json(name: 'placeOfBirth')]
    #[OpenApi\Property(
        property: 'placeOfBirth',
        type: 'string',
        description: 'Member schema place of birth'
    )]
    private ?string $placeOfBirth;

    /**
     * @var \Schnell\Decorator\Stringified\DateTimeDecorator|null
     */
    #[Rule(required: false)]
    #[Json(name: 'workDate')]
    #[Regex(pattern: self::ISO8601_DATE_PATTERN)]
    #[TransformedClassType(name: DateTimeDecorator::class)]
    #[OpenApi\Property(
        property: 'workDate',
        type: 'string',
        format: 'date',
        description: 'Member schema work date'
    )]
    private ?DateTimeDecorator $workDate;

    /**
     * @var string|null
     */
    #[Rule(required: false)]
    #[Json(name: 'workPlace')]
    #[Regex(pattern: self::ISO8601_DATE_PATTERN)]
    #[TransformedClassType(name: DateTimeDecorator::class)]
    #[OpenApi\Property(
        property: 'workPlace',
        type: 'string',
        description: 'Member schema work place'
    )]
    private ?string $workPlace;

    /**
     * @param string|null $identityNumber
     * @param string|null $postalCode
     * @param string|null $name
     * @param string|null $phoneNumber
     * @param string|null $address
     * @param \Pcta\Api\Type\Sex|null $sex
     * @param \Pcta\Api\Type\Religion|null $religion
     * @param \Pcta\Api\Type\Role|null $role
     * @param \Schnell\Decorator\Stringified\DateTimeDecorator|null $dateOfBirth
     * @param string|null $placeOfBirth
     * @param \Schnell\Decorator\Stringified\DateTimeDecorator|null $workDate
     * @param string|null $workPlace
     * @return static
     */
    public function __construct(
        ?string $identityNumber = null,
        ?string $postalCode = null,
        ?string $name = null,
        ?string $phoneNumber = null,
        ?string $address = null,
        ?SexType $sex = null,
        ?ReligionType $religion = null,
        ?RoleType $role = null,
        ?DateTimeDecorator $dateOfBirth = null,
        ?string $placeOfBirth = null,
        ?DateTimeDecorator $workDate = null,
        ?string $workPlace = null
    ) {
        $this->identityNumber = $identityNumber;
        $this->postalCode = $postalCode;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->sex = $sex;
        $this->religion = $religion;
        $this->role = $role;
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
     * @return \Pcta\Api\Type\Sex|null
     */
    public function getSex(): ?SexType
    {
        return $this->sex;
    }

    /**
     * @param \Pcta\Api\Type\Sex|null $sex
     * @return void
     */
    public function setSex(?SexType $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return \Pcta\Api\Type\Religion|null
     */
    public function getReligion(): ?ReligionType
    {
        return $this->religion;
    }

    /**
     * @param \Pcta\Api\Type\Religion|null $religion
     * @return void
     */
    public function setReligion(?ReligionType $religion): void
    {
        $this->religion = $religion;
    }

    /**
     * @return \Pcta\Api\Type\Role|null
     */
    public function getRole(): ?RoleType
    {
        return $this->role;
    }

    /**
     * @param \Pcta\Api\Type\Role|null $role
     * @return void
     */
    public function setRole(?RoleType $role): void
    {
        $this->role = $role;
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
