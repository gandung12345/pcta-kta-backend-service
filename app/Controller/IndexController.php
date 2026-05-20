<?php

declare(strict_types=1);

namespace Pcta\Api\Controller;

use Schnell\Attribute\Route;
use Schnell\Http\Code as HttpCode;
use Schnell\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Route::class);
class_exists(HttpCode::class);
class_exists(AbstractController::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class IndexController extends AbstractController
{
    #[Route('/', method: 'GET')]
    public function index(
        Request $request,
        Response $response,
        array $args
    ): Response {
        return $this->getContainer()
            ->get('twig')
            ->render($response, 'index.html.twig');
    }
}
