<?php

declare(strict_types=1);

namespace Pcta\Api\Controller;

use Schnell\Attribute\Route;
use Schnell\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class OpenApiController extends AbstractController
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    #[Route('/openapi', method: 'GET')]
    public function openApiMetadata(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $response->getBody()->write(
            $this->getContainer()->get('swagger')->toJson()
        );

        return $response->withHeader('Content-Type', 'application/json');
    }
}
