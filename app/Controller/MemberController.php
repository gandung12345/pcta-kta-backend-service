<?php

declare(strict_types=1);

namespace Pcta\Api\Controller;

use Throwable;
use OpenApi\Attributes as OpenApi;
use Pcta\Api\Entity\Member;
use Pcta\Api\Http\Response\Builder as ResponseBuilder;
use Pcta\Api\Repository\MemberRepository;
use Pcta\Api\Schema\MemberSchema;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Schnell\Attribute\Route;
use Schnell\Http\Code as HttpCode;
use Schnell\Paginator\Paginator;
use Schnell\Validator\Validator;

use function array_combine;
use function array_keys;
use function array_map;
use function array_values;
use function class_exists;
use function implode;
use function sprintf;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Member::class);
class_exists(ResponseBuilder::class);
class_exists(MemberRepository::class);
class_exists(MemberSchema::class);
class_exists(Route::class);
class_exists(Paginator::class);
class_exists(Validator::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[Route('/api/v1')]
class MemberController extends BaseController
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/member', method: 'GET')]
    #[OpenApi\Get(
        path: '/api/v1/member',
        tags: ['Member'],
        responses: [
            new OpenApi\Response(response: 200, description: 'OK')
        ]
    )]
    public function getAllMembers(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new MemberRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $count = $this->getContainer()
            ->get('mapper')
            ->withRequest($request)
            ->count(new Member());

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
    #[Route('/member/{id}', method: 'GET')]
    #[OpenApi\Get(
        path: '/api/v1/member/{id}',
        tags: ['Member'],
        responses: [
            new OpenApi\Response(response: 200, description: 'OK'),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function getMemberById(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new MemberRepository(
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
                ->withPair('message', sprintf('Member with id \'%s\' not found.', $args['id']));

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
    #[Route('/province/{pid}/municipal/{mid}/district/{did}/subdistrict/{sid}/member', method: 'POST')]
    #[OpenApi\Post(
        path: '/api/v1/province/{pid}/municipal/{mid}/district/{did}/subdistrict/{sid}/member',
        tags: ['Member'],
        responses: [
            new OpenApi\Response(response: 201, description: 'Created'),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function createMember(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $schema = new MemberSchema();
        $validator = new Validator();
        $validator = $validator->withRequest($request);
        $validator->assign($schema);

        $repository = new MemberRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->create(
            $args['pid'],
            $args['mid'],
            $args['did'],
            $args['sid'],
            $schema
        );

        if (null === $entity) {
            $dataPair = array_combine(
                ['province', 'municipal', 'district', 'subdistrict'],
                [$args['pid'], $args['mid'], $args['did'], $args['sid']]
            );

            $mappedPair = array_map(
                fn (string $key, string $value): string => sprintf('%s (id: %s)', $key, $value),
                array_keys($dataPair),
                array_values($dataPair)
            );

            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf(
                    'Failed to create new member. Check the availability of supplied data dependencies (%s)',
                    implode(', ', $mappedPair)
                ));

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
    #[Route('/member/{id}', method: 'PUT')]
    #[OpenApi\Put(
        path: '/api/v1/member/{id}',
        tags: ['Member'],
        responses: [
            new OpenApi\Response(response: 200, description: 'OK'),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function updateMember(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $schema = new MemberSchema();
        $validator = new Validator();
        $validator = $validator->withRequest($request);
        $validator->assignOptional($schema);

        $repository = new MemberRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->update($args['id'], $schema);

        if (null === $entity) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Member with id \'%s\' not found.', $args['id']));

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
    #[Route('/member/{id}/upload-image', method: 'PATCH')]
    #[OpenApi\Patch(
        path: '/api/v1/member/{id}/upload-image',
        tags: ['Member'],
        responses: [
            new OpenApi\Response(response: 200, description: 'OK'),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function uploadMemberImage(
        Request $request,
        Response $response,
        array $args
    ): Response {
        //
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/member/{id}', method: 'DELETE')]
    #[OpenApi\Delete(
        path: '/api/v1/member/{id}',
        tags: ['Member'],
        responses: [
            new OpenApi\Response(response: 204, description: 'No Content'),
            new OpenApi\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function removeMember(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $repository = new MemberRepository(
            $this->getContainer()->get('mapper'),
            $request
        );

        $entity = $repository->remove($args['id']);

        if (null === $entity) {
            $builder = new ResponseBuilder();
            $builder = $builder
                ->withPair('code', HttpCode::NOT_FOUND)
                ->withPair('message', sprintf('Member with id \'%s\' not found.', $args['id']));

            return $this->json($response, $builder->build(), HttpCode::NOT_FOUND);
        }

        return $this->response($response, '', HttpCode::NO_CONTENT);
    }
}
