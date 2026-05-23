<?php

declare(strict_types=1);

namespace Pcta\Api\Controller;

use Throwable;
use OpenApi\Attributes as OpenApi;
use Pcta\Api\Entity\Municipal;
use Pcta\Api\Http\Response\Builder as ResponseBuilder;
use Pcta\Api\Repository\MunicipalRepository;
use Pcta\Api\Schema\MunicipalSchema;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Schnell\Attribute\Route;
use Schnell\Http\Code as HttpCode;
use Schnell\Paginator\Paginator;
use Schnell\Validator\Validator;

use function class_exists;
use function sprintf;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Municipal::class);
class_exists(ResponseBuilder::class);
class_exists(MunicipalRepository::class);
class_exists(MunicipalSchema::class);
class_exists(Route::class);
class_exists(Paginator::class);
class_exists(Validator::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Route('/api/v1')]
class MunicipalController extends BaseController
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/municipal', method: 'GET')]
    #[OpenApi\Get(
        path: '/api/v1/municipal',
        tags: ['Municipal'],
        responses: [
            new OpenApi\Response(
                response: 200,
                description: 'OK',
                content: new OpenApi\JsonContent(
                    type: 'object',
                    properties: [
                        new OpenApi\Property(
                            property: 'data',
                            type: 'array',
                            items: new OpenApi\Items(ref: '#/components/schemas/Municipal')
                        ),
                        new OpenApi\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OpenApi\Property(property: 'page', type: 'integer', example: 1),
                                new OpenApi\Property(property: 'perPage', type: 'integer', example: 15),
                                new OpenApi\Property(property: 'offset', type: 'integer', example: 0),
                                new OpenApi\Property(property: 'totalCount', type: 'integer', example: 0),
                                new OpenApi\Property(property: 'pageCount', type: 'integer', example: 1)
                            ]
                        ),
                        new OpenApi\Property(
                            property: '_links',
                            type: 'object',
                            properties: [
                                new OpenApi\Property(
                                    property: 'self',
                                    type: 'string',
                                    example: 'http://foo.xyz/api/v1/district?page=1&perPage=15'
                                ),
                                new OpenApi\Property(property: 'prev', type: 'string', example: null),
                                new OpenApi\Property(
                                    property: 'next',
                                    type: 'string',
                                    example: 'http://foo.xyz/api/v1/district?page=2&perPage=15'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function getAllMunicipals(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new MunicipalRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $count = $this->getContainer()
            ->get('mapper')
            ->withRequest($request)
            ->count(new Municipal());

        $paginator = new Paginator($count);
        $page = $paginator->getMetadata($request);

        return $this->hateoas(
            $request,
            $response,
            $page,
            $repository->paginate()
        );
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/municipal/{id}', method: 'GET')]
    #[OpenApi\Get(
        path: '/api/v1/municipal/{id}',
        tags: ['Municipal'],
        parameters: [
            new OpenApi\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Municipal ID',
                schema: new OpenApi\Schema(type: 'string')
            )
        ],
        responses: [
            new OpenApi\Response(
                response: 200,
                description: 'OK',
                content: new OpenApi\JsonContent(ref: '#/components/schemas/Municipal')
            ),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function getMunicipalById(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new MunicipalRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        try {
            $result = $repository->getById($args['id']);
        } catch (Throwable $e) {
            $result = null;
        }

        if (null === $result) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Municipal with id \'%s\' not found.', $args['id']));

            return $this->json($response, $builder->build(), HttpCode::NOT_FOUND);
        }

        return $this->json($response, $result);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/province/{pid}/municipal', method: 'POST')]
    #[OpenApi\Post(
        path: '/api/v1/province/{pid}/municipal',
        tags: ['Municipal'],
        parameters: [
            new OpenApi\Parameter(
                name: 'pid',
                in: 'path',
                required: true,
                description: 'Province ID',
                schema: new OpenApi\Schema(type: 'string')
            )
        ],
        requestBody: new OpenApi\RequestBody(
            content: new OpenApi\JsonContent(ref: '#/components/schemas/MunicipalSchema')
        ),
        responses: [
            new OpenApi\Response(
                response: 201,
                description: 'Created',
                content: new OpenApi\JsonContent(ref: '#/components/schemas/Municipal')
            ),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function createMunicipal(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $schema = new MunicipalSchema();
        $validator = new Validator();
        $validator = $validator->withRequest($request);
        $validator->assign($schema);

        $repository = new MunicipalRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->create($args['pid'], $schema);

        if (null === $entity) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Province with id \'%s\' not found.', $args['pid']));

            return $this->json($response, $builder->build(), HttpCode::NOT_FOUND);
        }

        return $this->json($response, $entity, HttpCode::CREATED);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/municipal/{id}', method: 'PUT')]
    #[OpenApi\Put(
        path: '/api/v1/municipal/{id}',
        tags: ['Municipal'],
        parameters: [
            new OpenApi\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Municipal ID',
                schema: new OpenApi\Schema(type: 'string')
            )
        ],
        requestBody: new OpenApi\RequestBody(
            content: new OpenApi\JsonContent(ref: '#/components/schemas/MunicipalSchema')
        ),
        responses: [
            new OpenApi\Response(
                response: 200,
                description: 'OK',
                content: new OpenApi\JsonContent(ref: '#/components/schemas/Municipal')
            ),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function updateMunicipal(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $schema = new MunicipalSchema();
        $validator = new Validator();
        $validator = $validator->withRequest($request);
        $validator->assignOptional($schema);

        $repository = new MunicipalRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->update($args['id'], $schema);

        if (null === $entity) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Municipal with id \'%s\' not found.', $args['id']));

            return $this->json($response, $builder->build(), HttpCode::NOT_FOUND);
        }

        return $this->json($response, $entity);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/municipal/{id}', method: 'DELETE')]
    #[OpenApi\Delete(
        path: '/api/v1/municipal/{id}',
        tags: ['Municipal'],
        parameters: [
            new OpenApi\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Municipal ID',
                schema: new OpenApi\Schema(type: 'string')
            )
        ],
        responses: [
            new OpenApi\Response(response: 204, description: 'No Content'),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function removeMunicipal(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new MunicipalRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->remove($args['id']);

        if (null === $entity) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Municipal with id \'%s\' not found.', $args['id']));

            return $this->json($response, $builder->build(), HttpCode::NOT_FOUND);
        }

        return $this->response($response, '', HttpCode::NO_CONTENT);
    }
}
