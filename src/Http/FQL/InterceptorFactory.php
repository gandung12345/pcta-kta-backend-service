<?php

declare(strict_types=1);

namespace Schnell\Http\FQL;

use Override;
use Doctrine\ORM\QueryBuilder;
use Psr\Http\Message\RequestInterface;
use Schnell\Entity\EntityInterface;
use Schnell\Http\FQL\Lexer;
use Schnell\Http\FQL\Parser;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(QueryBuilder::class);
class_exists(Lexer::class);
class_exists(Parser::class);
// phpcs:enable

/**
 * @psalm-api
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class InterceptorFactory implements InterceptorFactoryInterface
{
    /**
     * @var \Psr\Http\Message\RequestInterface|null
     */
    private ?RequestInterface $request;

    /**
     * @var \Doctrine\ORM\QueryBuilder|null
     */
    private ?QueryBuilder $queryBuilder;

    /**
     * @var \Schnell\Entity\EntityInterface|null
     */
    private ?EntityInterface $entity;

    /**
     * @psalm-api
     *
     * @param \Psr\Http\Message\RequestInterface|null $request
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder
     * @param \Schnell\Entity\EntityInterface|null $entity
     * @return static
     */
    public function __construct(
        ?RequestInterface $request,
        ?QueryBuilder $queryBuilder,
        ?EntityInterface $entity
    ) {
        $this->setRequest($request);
        $this->setQueryBuilder($queryBuilder);
        $this->setEntity($entity);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setRequest(?RequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getQueryBuilder(): ?QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setQueryBuilder(?QueryBuilder $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getEntity(): ?EntityInterface
    {
        return $this->entity;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function setEntity(?EntityInterface $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function createInterceptor(): ?InterceptorInterface
    {
        if (($queryParams = $this->getFilterQuery()) === null) {
            return null;
        }

        $lexer = new Lexer();
        $lexer->setBuffer($queryParams);
        $lexer->lex();

        $parser = new Parser(
            $lexer->getTokens(),
            $this->getQueryBuilder(),
            $this->getEntity()
        );

        $parser->parse();

        return new Interceptor(
            $this->getQueryBuilder(),
            $parser->getAst(),
            $this->getEntity()
        );
    }

    /**
     * @return string|null
     */
    private function getFilterQuery(): ?string
    {
        /**
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $queryParams = $this->getRequest()->getQueryParams();

        if (!isset($queryParams['filter']) || $queryParams['filter'] === '') {
            return null;
        }

        return $queryParams['filter'];
    }
}
