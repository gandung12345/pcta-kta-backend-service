<?php

declare(strict_types=1);

namespace Pcta\Api\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use OpenApi\Attributes as OpenApi;
use Pcta\Api\Type\Religion as ReligionType;
use Pcta\Api\Type\Role as RoleType;
use Pcta\Api\Type\Sex as SexType;
use Schnell\Attribute\Schema\Json;
use Schnell\Decorator\Stringified\DateTimeDecorator;
use Schnell\Entity\AbstractEntity;
use Schnell\Entity\EntityInterface;

use function class_exists;
use function sprintf;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(DateTime::class);
class_exists(Types::class);
class_exists(Entity::class);
class_exists(Table::class);
class_exists(Id::class);
class_exists(Column::class);
class_exists(PrePersist::class);
class_exists(PreUpdate::class);
class_exists(HasLifecycleCallbacks::class);
class_exists(Json::class);
class_exists(DateTimeDecorator::class);
class_exists(AbstractEntity::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Entity]
#[Table(name: 'member')]
#[HasLifecycleCallbacks]
#[OpenApi\Schema]
class Member extends AbstractEntity
{
    /**
     * @var string
     */
    #[Id]
    #[Column(type: 'guid', nullable: false, unique: true)]
    #[Json(name: 'id')]
    #[OpenApi\Property(
        property: 'id',
        type: 'string',
        description: 'Member ID',
        readOnly: true
    )]
    private string $id;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'identityNumber')]
    #[OpenApi\Property(
        property: 'identityNumber',
        type: 'string',
        description: 'Member identity number'
    )]
    private string $identityNumber;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'membershipIdentityNumber')]
    #[OpenApi\Property(
        property: 'membershipIdentityNumber',
        type: 'string',
        description: 'Membership identity number'
    )]
    private string $membershipIdentityNumber;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'postalCode')]
    #[OpenApi\Property(
        property: 'postalCode',
        type: 'string',
        description: 'Member postal code'
    )]
    private string $postalCode;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'name')]
    #[OpenApi\Property(
        property: 'name',
        type: 'string',
        description: 'Member name'
    )]
    private string $name;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'phoneNumber')]
    #[OpenApi\Property(
        property: 'phoneNumber',
        type: 'string',
        description: 'Member phone number'
    )]
    private string $phoneNumber;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'address')]
    #[OpenApi\Property(
        property: 'address',
        type: 'string',
        description: 'Member address'
    )]
    private string $address;

    #[Column(type: 'string', nullable: false, enumType: SexType::class)]
    #[Json(name: 'sex')]
    #[OpenApi\Property(
        property: 'sex',
        type: 'string',
        description: 'Member sex type'
    )]
    private SexType $sex;

    #[Column(type: 'string', nullable: false, enumType: ReligionType::class)]
    #[Json(name: 'religion')]
    #[OpenApi\Property(
        property: 'religion',
        type: 'string',
        description: 'Member religion type'
    )]
    private ReligionType $religion;

    #[Column(type: 'string', nullable: false, enumType: RoleType::class)]
    #[Json(name: 'role')]
    #[OpenApi\Property(
        property: 'role',
        type: 'string',
        description: 'Member role type'
    )]
    private RoleType $role;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'image')]
    #[OpenApi\Property(
        property: 'image',
        type: 'string',
        description: 'Member image'
    )]
    private string $image;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'qrCodeImage')]
    #[OpenApi\Property(
        property: 'qrCodeImage',
        type: 'string',
        description: 'Member QRCode image'
    )]
    private string $qrCodeImage;

    /**
     * @var \Schnell\Decorator\Stringified\DateTimeDecorator
     */
    #[Column(type: 'date', nullable: false)]
    #[Json(name: 'dateOfBirth')]
    #[OpenApi\Property(
        property: 'dateOfBirth',
        type: 'string',
        format: 'date',
        description: 'Member date of birth'
    )]
    private DateTimeDecorator $dateOfBirth;

    /**
     * @var string
     */
    #[Column(type: 'string', nullable: false)]
    #[Json(name: 'placeOfBirth')]
    #[OpenApi\Property(
        property: 'placeOfBirth',
        type: 'string',
        description: 'Member place of birth'
    )]
    private string $placeOfBirth;

    /**
     * @var \Schnell\Decorator\Stringified\DateTimeDecorator|null
     */
    #[Column(type: 'date', nullable: true)]
    #[Json(name: 'workDate')]
    #[OpenApi\Property(
        property: 'workDate',
        type: 'string',
        format: 'date',
        description: 'Member work date'
    )]
    private ?DateTimeDecorator $workDate;

    /**
     * @var string
     */
    #[Column(type: 'string', nullable: true)]
    #[Json(name: 'workPlace')]
    #[OpenApi\Property(
        property: 'workPlace',
        type: 'string',
        description: 'Member work place'
    )]
    private ?string $workPlace;

    #[OneToOne(targetEntity: Province::class, inversedBy: 'member')]
    #[JoinColumn(name: 'provinceRefId', referencedColumnName: 'id')]
    private Province $province;

    #[OneToOne(targetEntity: Municipal::class, inversedBy: 'member')]
    #[JoinColumn(name: 'municipalRefId', referencedColumnName: 'id')]
    private Municipal $municipal;

    #[OneToOne(targetEntity: District::class, inversedBy: 'member')]
    #[JoinColumn(name: 'districtRefId', referencedColumnName: 'id')]
    private District $district;

    #[OneToOne(targetEntity: Subdistrict::class, inversedBy: 'member')]
    #[JoinColumn(name: 'subdistrictRefId', referencedColumnName: 'id')]
    private Subdistrict $subdistrict;

    /**
     * @var \DateTime|null
     */
    #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Json(name: 'createdAt')]
    #[OpenApi\Property(
        property: 'createdAt',
        type: 'timestamp',
        description: 'Member created at'
    )]
    private ?DateTime $createdAt;

    /**
     * @var \DateTime|null
     */
    #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Json(name: 'updatedAt')]
    #[OpenApi\Property(
        property: 'updatedAt',
        type: 'timestamp',
        description: 'Member updated at'
    )]
    private ?DateTime $updatedAt;

    /**
     * @return void
     */
    #[PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAtValue();
    }

    /**
     * @return void
     */
    #[PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIdentityNumber(): string
    {
        return $this->identityNumber;
    }

    /**
     * @param string $identityNumber
     * @return void
     */
    public function setIdentityNumber(string $identityNumber): void
    {
        $this->identityNumber = $identityNumber;
    }

    /**
     * @return string
     */
    public function getMembershipIdentityNumber(): string
    {
        return $this->membershipIdentityNumber;
    }

    /**
     * @param string $membershipIdentityNumber
     * @return void
     */
    public function setMembershipIdentityNumber(string $membershipIdentityNumber): void
    {
        $this->membershipIdentityNumber = $membershipIdentityNumber;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return void
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
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
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return void
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return void
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return void
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getQrCodeImage(): string
    {
        return $this->qrCodeImage;
    }

    /**
     * @param string $qrCodeImage
     * @return void
     */
    public function setQrCodeImage(string $qrCodeImage): void
    {
        $this->qrCodeImage = $qrCodeImage;
    }

    /**
     * @return \Schnell\Decorator\Stringified\DateTimeDecorator
     */
    public function getDateOfBirth(): DateTimeDecorator
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \Schnell\Decorator\Stringified\DateTimeDecorator $dateOfBirth
     * @return void
     */
    public function setDateOfBirth(DateTimeDecorator $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return string
     */
    public function getPlaceOfBirth(): string
    {
        return $this->placeOfBirth;
    }

    /**
     * @param string $dateOfBirth
     * @return void
     */
    public function setPlaceOfBirth(string $placeOfBirth): void
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

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \Schnell\Entity\EntityInterface
     */
    public function getProvince(): EntityInterface
    {
        return $this->province;
    }

    /**
     * @param \Schnell\Entity\EntityInterface $province
     * @return void
     */
    public function setProvince(EntityInterface $province): void
    {
        $this->province = $province;
    }

    /**
     * @return \Schnell\Entity\EntityInterface
     */
    public function getMunicipal(): EntityInterface
    {
        return $this->municipal;
    }

    /**
     * @param \Schnell\Entity\EntityInterface $municipal
     * @return void
     */
    public function setMunicipal(EntityInterface $municipal): void
    {
        $this->municipal = $municipal;
    }

    /**
     * @return \Schnell\Entity\EntityInterface
     */
    public function getDistrict(): EntityInterface
    {
        return $this->district;
    }

    /**
     * @param \Schnell\Entity\EntityInterface $district
     * @return void
     */
    public function setDistrict(EntityInterface $district): void
    {
        $this->district = $district;
    }

    /**
     * @return \Schnell\Entity\EntityInterface
     */
    public function getSubdistrict(): EntityInterface
    {
        return $this->subdistrict;
    }

    /**
     * @param \Schnell\Entity\EntityInterface $subdistrict
     * @return void
     */
    public function setSubdistrict(EntityInterface $subdistrict): void
    {
        $this->subdistrict = $subdistrict;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryBuilderAlias(): string
    {
        return sprintf('__%s__', $this->getCanonicalTableName());
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalTableName(): string
    {
        return 'member';
    }

    /**
     * {@inheritDoc}
     */
    public function getDqlName(): string
    {
        return Member::class;
    }
}
