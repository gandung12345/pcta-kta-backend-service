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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use OpenApi\Attributes as OpenApi;
use Schnell\Attribute\Schema\Json;
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
class_exists(JoinColumn::class);
class_exists(ManyToOne::class);
class_exists(PrePersist::class);
class_exists(PreUpdate::class);
class_exists(HasLifecycleCallbacks::class);
class_exists(Json::class);
class_exists(AbstractEntity::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Entity]
#[Table(name: 'subDistrict')]
#[HasLifecycleCallbacks]
#[OpenApi\Schema(schema: 'Subdistrict', type: 'object')]
class Subdistrict extends AbstractEntity
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
        description: 'Subdistrict ID',
        readOnly: true
    )]
    private string $id;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'formalIdentifier')]
    #[OpenApi\Property(
        property: 'formalIdentifier',
        type: 'string',
        description: 'Subdistrict formal identifier'
    )]
    private string $formalIdentifier;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: false)]
    #[Json(name: 'formalName')]
    #[OpenApi\Property(
        property: 'formalName',
        type: 'string',
        description: 'Subdistrict formal name'
    )]
    private string $formalName;

    /**
     * @var \DateTime|null
     */
    #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Json(name: 'createdAt')]
    #[OpenApi\Property(
        property: 'createdAt',
        type: 'timestamp',
        description: 'Subdistrict created at',
        readOnly: true
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
        description: 'Subdistrict updated at',
        readOnly: true
    )]
    private ?DateTime $updatedAt;

    #[ManyToOne(
        targetEntity: District::class,
        inversedBy: 'subdistricts',
        cascade: ['persist']
    )]
    #[JoinColumn(name: 'districtRefId', referencedColumnName: 'id')]
    private District $district;

    #[OneToOne(targetEntity: Member::class, mappedBy: 'subdistrict')]
    private Member $member;

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
    public function getFormalIdentifier(): string
    {
        return $this->formalIdentifier;
    }

    /**
     * @param string $formalIdentifier
     * @return void
     */
    public function setFormalIdentifier(string $formalIdentifier): void
    {
        $this->formalIdentifier = $formalIdentifier;
    }

    /**
     * @return string
     */
    public function getFormalName(): string
    {
        return $this->formalName;
    }

    /**
     * @param string $formalName
     * @return void
     */
    public function setFormalName(string $formalName): void
    {
        $this->formalName = $formalName;
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
    public function getMember(): EntityInterface
    {
        return $this->member;
    }

    /**
     * @param \Schnell\Entity\EntityInterface $member
     * @return void
     */
    public function setMember(EntityInterface $member): void
    {
        $this->member = $member;
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
        return 'subDistrict';
    }

    /**
     * {@inheritDoc}
     */
    public function getDqlName(): string
    {
        return Subdistrict::class;
    }
}
