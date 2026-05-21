<?php

declare(strict_types=1);

namespace Schnell\Middleware;

use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Schnell\Controller\ControllerPoolInterface;
use Slim\Psr7\Response;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(Response::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class CorsMiddleware implements MiddlewareInterface
{
    use MiddlewareTrait;

    /**
     * @var \Schnell\Controller\ControllerPoolInterface
     */
    private ControllerPoolInterface $controllerPool;

    /**
     * @psalm-api
     *
     * @param \Schnell\Controller\ControllerPoolInterface $controllerPool
     * @return static
     */
    public function __construct(ControllerPoolInterface $controllerPool)
    {
        $this->setControllerPool($controllerPool);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getControllerPool(): ControllerPoolInterface
    {
        return $this->controllerPool;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function setControllerPool(ControllerPoolInterface $controllerPool): void
    {
        $this->controllerPool = $controllerPool;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $request->getMethod() === 'OPTIONS'
            ? new Response()
            : $handler->handle($request);

        return $this->injectCorsHeader($response);
    }
}
