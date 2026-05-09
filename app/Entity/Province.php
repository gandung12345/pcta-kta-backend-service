<?php

declare(strict_types=1);

namespace Pcta\Api\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

use function class_exists;
use function sprintf;

// help opcache.preload discover always-needed symbols
// phpcs:disable
// phpcs:enable

#[Entity]
#[Table(name: 'province')]
#[HasLifecycleCallbacks]
#[OpenApi\Schema]
class Province extends AbstractEntity
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
        description: 'Province ID',
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
        description: 'Province formal identifier',
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
        description: 'Province formal name'
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
        description: 'Province created at'
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
        description: 'Province updated at'
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
        return 'province';
    }

    /**
     * {@inheritDoc}
     */
    public function getDqlName(): string
    {
        return Province::class;
    }
}
