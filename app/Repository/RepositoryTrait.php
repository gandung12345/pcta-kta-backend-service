<?php

declare(strict_types=1);

namespace Pcta\Api\Repository;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Query\Expr;
use Pcta\Api\Repository\Exception\RepositoryException;
use Schnell\Entity\EntityInterface;
use Schnell\Hydrator\MapHydrator;
use Psr\Http\Message\ServerRequestInterface;

use function array_map;
use function call_user_func;
use function class_exists;
use function in_array;
use function sizeof;
use function sprintf;
use function ucfirst;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(ReflectionAttribute::class);
class_exists(ReflectionClass::class);
class_exists(ReflectionProperty::class);
class_exists(JoinColumn::class);
class_exists(Expr::class);
class_exists(RepositoryException::class);
class_exists(MapHydrator::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
trait RepositoryTrait
{
    /**
     * @internal
     *
     * @param array $result
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    private function hydrateListWithParent(
        array $result,
        ServerRequestInterface $request
    ): array {
        $results = [];
        $showParentActive = $this->isShowParentActive($request);

        foreach ($result as $entity) {
            $results[] = $showParentActive
                ? $this->hydrateParent($entity)
                : MapHydrator::create()->hydrate($entity);
        }

        return $results;
    }

    /**
     * @internal
     *
     * @param \Schnell\Entity\EntityInterface $entity
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    private function hydrateEntityWithParent(
        EntityInterface $entity,
        ServerRequestInterface $request
    ): array {
        return $this->isShowParentActive($request)
            ? $this->hydrateParent($entity)
            : MapHydrator::create()->hydrate($entity);
    }

    /**
     * @internal
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    private function isShowParentActive(ServerRequestInterface $request): bool
    {
        $queryParams = $request->getQueryParams();

        if (
            isset($queryParams['showParent']) &&
            false === in_array($queryParams['showParent'], ['true', 'false'], true)
        ) {
            throw new RepositoryException(
                $request,
                "'showParent' query string must be either 'true' or 'false'."
            );
        }

        if (!isset($queryParams['showParent']) || $queryParams['showParent'] === 'false') {
            return false;
        }

        return true;
    }

    /**
     * @internal
     *
     * @param \Schnell\Entity\EntityInterface $entity
     * @return array
     */
    private function hydrateParent(EntityInterface $entity): array
    {
        return $this->mergeAndPopulateParent(
            $entity,
            MapHydrator::create()->hydrate($entity)
        );
    }

    /**
     * @internal
     *
     * @param \Schnell\Entity\EntityInterface $entity
     * @param array $results
     * @return array
     */
    private function mergeAndPopulateParent(EntityInterface $entity, array $results): array
    {
        $newResults = $results;
        $reflection = new ReflectionClass($entity);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $attributeNames = array_map(
                fn (ReflectionAttribute $attribute): string => $attribute->getName(),
                $property->getAttribute()
            );

            if (!in_array(JoinColumn::class, $attributeNames, true)) {
                continue;
            }

            $key = sprintf('@%s', $property->getName());
            $parent = call_user_func([$entity, sprintf('get%s', ucfirst($property->getName()))]);

            if (null === $parent) {
                continue;
            }

            $result = $this->fetchParent($parent, $entity, $property->getName());
            $newResults[$key] = MapHydrator::create()->hydrate($result);
        }

        return $newResults;
    }

    /**
     * @internal
     *
     * @param \Schnell\Entity\EntityInterface $parent
     * @param \Schnell\Entity\EntityInterface $child
     * @param string $parentPropertyName
     * @return \Schnell\Entity\EntityInterface|null
     */
    private function fetchParent(
        EntityInterface $parent,
        EntityInterface $child,
        string $parentPropertyName
    ): ?EntityInterface {
        $entityManager = $this->getMapper()
            ->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $result = $queryBuilder
            ->select($parent->getQueryBuilderAlias())
            ->from($parent->getDqlName(), $parent->getQueryBuilderAlias())
            ->join(
                $child->getDqlName(),
                $child->getQueryBuilderAlias(),
                Expr\Join::WITH,
                sprintf(
                    '%s.id = %s.%s',
                    $parent->getQueryBuilderAlias(),
                    $child->getQueryBuilderAlias(),
                    $parentPropertyName
                )
            )
            ->where($queryBuilder->expr()->eq(
                sprintf('%s.id', $parent->getQueryBuilderAlias()),
                '?1'
            ))
            ->setParameter(1, $parent->getId())
            ->getQuery()
            ->getResult();

        if (sizeof($result) !== 1) {
            return null;
        }

        return $result[0];
    }
}
