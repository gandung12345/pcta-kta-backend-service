<?php

declare(strict_types=1);

namespace Pcta\Api\Repository;

use Doctrine\ORM\Query\Expr;
use Pcta\Api\Entity\District;
use Pcta\Api\Entity\Municipal;
use Schnell\Entity\EntityInterface;
use Schnell\Hydrator\MapHydrator;
use Schnell\Paginator\Paginator;
use Schnell\Repository\AbstractRepository;
use Schnell\Schema\SchemaInterface;
use Symfony\Component\Uid\Uuid;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Expr::class);
class_exists(District::class);
class_exists(Municipal::class);
class_exists(MapHydrator::class);
class_exists(Paginator::class);
class_exists(AbstractRepository::class);
class_exists(Uuid::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class DistrictRepository extends AbstractRepository
{
    use RepositoryTrait;

    /**
     * @return array
     */
    public function paginate(): array
    {
        $count = $this->getMapper()
            ->withRequest($this->getRequest())
            ->count(new District());
        $paginator = new Paginator($count);
        $page = $paginator->getMetadata($this->getRequest());
        $result = $this->getMapper()
            ->withPage($page)
            ->withRequest($this->getRequest())
            ->paginate(new District());

        return $this->hydrateListWithParent($result, $this->getRequest());
    }

    public function getById($id): EntityInterface|array|null
    {
        $entity = $this->getMapper()->find(new District(), $id);

        if (null === $entity) {
            return null;
        }

        return $this->hydrateEntityWithParent($entity, $this->getRequest());
    }

    public function create($refId, SchemaInterface $schema): EntityInterface|array|null
    {
        $district = new District();
        $district->setId(Uuid::v7()->toString());

        $entity = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->createReferenced(
                $refId,
                $schema,
                $district,
                new Municipal()
            );

        return $entity;
    }

    public function update($id, SchemaInterface $schema): EntityInterface|array|null
    {
        $result = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->update($id, $schema, new District());

        return $result;
    }

    public function remove($id): EntityInterface|array|null
    {
        $result = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->remove($id, new District());

        return $result;
    }
}
