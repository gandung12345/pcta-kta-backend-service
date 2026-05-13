<?php

declare(strict_types=1);

namespace Pcta\Api\Repository;

use Pcta\Api\Entity\District;
use Pcta\Api\Entity\Member;
use Pcta\Api\Entity\Municipal;
use Pcta\Api\Entity\Province;
use Pcta\Api\Entity\Subdistrict;
use Schnell\Entity\EntityInterface;
use Schnell\Hydrator\MapHydrator;
use Schnell\Repository\AbstractRepository;
use Schnell\Paginator\Paginator;
use Schnell\Schema\SchemaInterface;
use Symfony\Component\Uid\Uuid;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(District::class);
class_exists(Member::class);
class_exists(Municipal::class);
class_exists(Province::class);
class_exists(Subdistrict::class);
class_exists(MapHydrator::class);
class_exists(AbstractRepository::class);
class_exists(Paginator::class);
class_exists(Uuid::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class MemberRepository extends AbstractRepository
{
    use RepositoryTrait;

    /**
     * @return array
     */
    public function paginate(): array
    {
        $count = $this->getMapper()
            ->withRequest($this->getRequest())
            ->count(new Member());
        $paginator = new Paginator($count);
        $page = $paginator->getMetadata($this->getRequest());
        $result = $this->getMapper()
            ->withPage($page)
            ->withRequest($this->getRequest())
            ->paginate(new Member());

        return $this->hydrateListWithParent($result, $this->getRequest());
    }

    /**
     * @param mixed $id
     * @return \Schnell\Entity\EntityInterface|array|null
     */
    public function getById($id): EntityInterface|array|null
    {
        $entity = $this->getMapper()->find(new Member(), $id);

        if (null === $entity) {
            return null;
        }

        return $this->hydrateEntityWithParent($entity, $this->getRequest());
    }

    /**
     * @param string $refProvinceId
     * @param string $refMunicipalId
     * @param string $refDistrictId
     * @param string $refSubdistrictId
     * @param \Schnell\Schema\SchemaInterface $schema
     * @return \Schnell\Entity\EntityInterface|array|null
     */
    public function create(
        string $refProvinceId,
        string $refMunicipalId,
        string $refDistrictId,
        string $refSubdistrictId,
        SchemaInterface $schema
    ): EntityInterface|array|null {
        $member = new Member();
        $member->setId(Uuid::v7()->toString());

        $entity = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->createFromMultipleReference(
                [$refProvinceId, $refMunicipalId, $refDistrictId, $refSubdistrictId],
                [new Province(), new Municipal(), new District(), new Subdistrict()],
                $schema,
                $member
            );

        return $entity;
    }

    /**
     * @param mixed $id
     * @param \Schnell\Schema\SchemaInterface $schema
     * @return \Schnell\Entity\EntityInterface|array|null
     */
    public function update($id, SchemaInterface $schema): EntityInterface|array|null
    {
        $result = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->update($id, $schema, new Member());

        return $result;
    }

    /**
     * @param mixed $id
     * @return \Schnell\Entity\EntityInterface|array|null
     */
    public function remove($id): EntityInterface|array|null
    {
        $result = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->remove($id, new Member());

        return $result;
    }
}
