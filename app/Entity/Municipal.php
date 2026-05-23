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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\PersistentCollection;
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
class_exists(OneToMany::class);
class_exists(PrePersist::class);
class_exists(PreUpdate::class);
class_exists(HasLifecycleCallbacks::class);
class_exists(PersistentCollection::class);
class_exists(Json::class);
class_exists(AbstractEntity::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Entity]
#[Table(name: 'municipal')]
#[HasLifecycleCallbacks]
#[OpenApi\Schema(schema: 'Municipal', type: 'object')]
class Municipal extends AbstractEntity
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
        description: 'Municipal ID',
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
        description: 'Municipal formal identifier'
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
        description: 'Municipal formal name'
    )]
    private string $formalName;

    /**
     * @var \DateTime|null
     */
    #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Json(name: 'createdAt')]
    #[OpenApi\Property(
        property: 'createdAt',
        type: 'string',
        format: 'datetime',
        description: 'Municipal created at',
        example: '1970-01-01 00:00:00',
        pattern: '^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}',
        readOnly: true
    )]
    private ?DateTime $createdAt;

    /**
     * @var \DateTime|null
     */
    #[Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Json(name: 'updatedAt')]
    #[OpenApi\Property(
        property: 'updatedAt',
        type: 'string',
        format: 'datetime',
        description: 'Municipal updated at',
        example: '1970-01-01 00:00:00',
        pattern: '^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}',
        readOnly: true
    )]
    private ?DateTime $updatedAt;

    #[ManyToOne(
        targetEntity: Province::class,
        inversedBy: 'municipals',
        cascade: ['persist']
    )]
    #[JoinColumn(name: 'provinceRefId', referencedColumnName: 'id')]
    private Province $province;

    #[OneToMany(
        targetEntity: District::class,
        mappedBy: 'municipal',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private PersistentCollection $districts;

    #[OneToOne(targetEntity: Member::class, mappedBy: 'municipal')]
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
     * @return \Doctrine\ORM\PersistentCollection
     */
    public function getDistricts(): PersistentCollection
    {
        return $this->districts;
    }

    /**
     * @param \Doctrine\ORM\PersistentCollection $districts
     * @return void
     */
    public function setDistricts(PersistentCollection $districts): void
    {
        $this->districts = $districts;
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
        return 'municipal';
    }

    /**
     * {@inheritDoc}
     */
    public function getDqlName(): string
    {
        return Municipal::class;
    }
}
