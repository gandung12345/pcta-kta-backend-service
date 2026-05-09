<?php

declare(strict_types=1);

namespace Pcta\Api\Controller;

use Throwable;
use OpenApi\Attributes as OpenApi;
use Pcta\Api\Entity\Member;
use Pcta\Api\Http\Response\Builder as ResponseBuilder;
use Pcta\Api\Repository\MemberRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Schnell\Attribute\Route;
use Schnell\Paginator\Paginator;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class MemberController extends BaseController
{
    #[Route('/member', method: 'GET')]
    #[OpenApi\Get(
        path: '/member',
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

    #[Route('/member/{id}', method: 'GET')]
    #[OpenApi\Get(
        path: '/member/{id}',
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

    #[Route('/province/{pid}/municipal/{mid}/district/{did}/subdistrict/{sid}/member', method: 'POST')]
    #[OpenApi\Post(
        path: '/province/{pid}/municipal/{mid}/district/{did}/subdistrict/{sid}/member',
        tags: ['Member'],
        responses: [
            new OpenApi\Response(response: 201, description: 'Created')
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

        $entity =
    }
}
