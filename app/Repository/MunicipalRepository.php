<?php

declare(strict_types=1);

namespace Pcta\Api\Repository;

use Pcta\Api\Entity\Municipal;
use Pcta\Api\Entity\Province;
use Schnell\Entity\EntityInterface;
use Schnell\Hydrator\MapHydrator;
use Schnell\Paginator\Paginator;
use Schnell\Repository\AbstractRepository;
use Schnell\Schema\SchemaInterface;
use Symfony\Component\Uid\Uuid;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Municipal::class);
class_exists(Province::class);
class_exists(MapHydrator::class);
class_exists(Paginator::class);
class_exists(AbstractRepository::class);
class_exists(Uuid::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class MunicipalRepository extends AbstractRepository
{
    use RepositoryTrait;

    public function paginate(): array
    {
        $count = $this->getMapper()
            ->withRequest($this->getRequest())
            ->count(new Municipal());
        $paginator = new Paginator($count);
        $page = $paginator->getMetadata($this->getRequest());
        $result = $this->getMapper()
            ->withPage($page)
            ->withRequest($request)
            ->paginate(new Municipal());

        return $this->hydrateListWithParent($result, $this->getRequest());
    }

    public function getById($id): EntityInterface|array|null
    {
        $entity = $this->getMapper()->find(new Municipal(), $id);

        if (null === $entity) {
            return null;
        }

        return $this->hydrateEntityWithParent($entity, $this->getRequest());
    }

    public function create($refId, SchemaInterface $schema): EntityInterface|array|null
    {
        $municipal = new Municipal();
        $municipal->setId(Uuid::v7()->toString());

        $entity = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->createReferenced(
                $refId,
                $schema,
                $municipal,
                new Province()
            );

        return $entity;
    }

    public function update($id, SchemaInterface $schema): EntityInterface|array|null
    {
        $result = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->update($id, $schema, new Municipal());

        return $result;
    }

    public function remove($id): EntityInterface|array|null
    {
        $result = $this->getMapper()
            ->withHydrator(new MapHydrator())
            ->remove($id, new Municipal());

        return $result;
    }
}
