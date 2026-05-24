<?php

declare(strict_types=1);

namespace Pcta\Api\Controller;

use Throwable;
use OpenApi\Attributes as OpenApi;
use Pcta\Api\Entity\Province;
use Pcta\Api\Http\Response\Builder as ResponseBuilder;
use Pcta\Api\Repository\ProvinceRepository;
use Pcta\Api\Schema\ProvinceSchema;
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
class_exists(Province::class);
class_exists(ResponseBuilder::class);
class_exists(ProvinceRepository::class);
class_exists(ProvinceSchema::class);
class_exists(Route::class);
class_exists(Paginator::class);
class_exists(Validator::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Route('/api/v1')]
class ProvinceController extends BaseController
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/province', method: 'GET')]
    #[OpenApi\Get(
        path: '/api/v1/province',
        tags: ['Province'],
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
                            items: new OpenApi\Items(ref: '#/components/schemas/Province')
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
                                    example: 'http://foo.xyz/api/v1/province?page=1&perPage=15'
                                ),
                                new OpenApi\Property(property: 'prev', type: 'string', example: null),
                                new OpenApi\Property(
                                    property: 'next',
                                    type: 'string',
                                    example: 'http://foo.xyz/api/v1/province?page=1&perPage=15'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function getAllProvinces(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new ProvinceRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $count = $this->getContainer()
            ->get('mapper')
            ->withRequest($request)
            ->count(new Province());

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
    #[Route('/province/{id}', method: 'GET')]
    #[OpenApi\Get(
        path: '/api/v1/province/{id}',
        tags: ['Province'],
        parameters: [
            new OpenApi\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Province ID',
                schema: new OpenApi\Schema(type: 'string')
            )
        ],
        responses: [
            new OpenApi\Response(
                response: 200,
                description: 'OK',
                content: new OpenApi\JsonContent(ref: '#/components/schemas/Province')
            ),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function getProvinceById(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new ProvinceRepository(
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
                ->withPair('message', sprintf('Province with id \'%s\' not found.', $args['id']));

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
    #[Route('/province', method: 'POST')]
    #[OpenApi\Post(
        path: '/api/v1/province',
        tags: ['Province'],
        requestBody: new OpenApi\RequestBody(
            content: new OpenApi\JsonContent(ref: '#/components/schemas/ProvinceSchema')
        ),
        responses: [
            new OpenApi\Response(
                response: 201,
                description: 'Created',
                content: new OpenApi\JsonContent(ref: '#/components/schemas/Province')
            )
        ]
    )]
    public function createProvince(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $schema = new ProvinceSchema();
        $validator = new Validator();
        $validator = $validator->withRequest($request);
        $validator->assign($schema);

        $repository = new ProvinceRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->create($schema);

        return $this->json($response, $entity, HttpCode::CREATED);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/province/{id}', method: 'PUT')]
    #[OpenApi\Put(
        path: '/api/v1/province/{id}',
        tags: ['Province'],
        parameters: [
            new OpenApi\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Province ID',
                schema: new OpenApi\Schema(type: 'string')
            )
        ],
        requestBody: new OpenApi\RequestBody(
            content: new OpenApi\JsonContent(ref: '#/components/schemas/ProvinceSchema')
        ),
        responses: [
            new OpenApi\Response(
                response: 200,
                description: 'OK',
                content: new OpenApi\JsonContent(ref: '#/components/schemas/Province')
            ),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function updateProvince(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $schema = new ProvinceSchema();
        $validator = new Validator();
        $validator = $validator->withRequest($request);
        $validator->assignOptional($schema);

        $repository = new ProvinceRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->update($args['id'], $schema);

        if (null === $entity) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Province with id \'%s\' not found.', $args['id']));

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
    #[Route('/province/{id}', method: 'DELETE')]
    #[OpenApi\Delete(
        path: '/api/v1/province/{id}',
        tags: ['Province'],
        parameters: [
            new OpenApi\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Province ID',
                schema: new OpenApi\Schema(type: 'string')
            )
        ],
        responses: [
            new OpenApi\Response(response: 204, description: 'No Content'),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function removeProvince(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new ProvinceRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->remove($args['id']);

        if (null === $entity) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Province with id \'%s\' not found.', $args['id']));

            return $this->json($response, $builder->build(), HttpCode::NOT_FOUND);
        }

        return $this->response($response, '', HttpCode::NO_CONTENT);
    }
}
