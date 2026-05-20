<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';

use Pcta\Api\Middleware\RoleMiddleware;
use Schnell\Container;
use Schnell\ContainerInterface;
use Schnell\Kernel;
use Schnell\Bridge\Cache\CacheBridge;
use Schnell\Bridge\Doctrine\DoctrineBridge;
use Schnell\Bridge\Mapper\MapperBridge;
use Schnell\Bridge\SlimCacheProvider\SlimCacheProviderBridge;
use Schnell\Bridge\Swagger\SwaggerBridge;
use Schnell\Bridge\Twig\TwigBridge;
use Schnell\Config\ConfigFactory;
use Schnell\Config\ConfigInterface;
use Schnell\Controller\ControllerPool;
use Schnell\Controller\ControllerResolver;
use Schnell\Middleware\CorsMiddleware;
use Schnell\Middleware\HttpErrorMiddleware;
use Schnell\Middleware\ContentTypeMiddleware;

use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\TwigMiddleware;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(RoleMiddleware::class);
class_exists(Container::class);
class_exists(Kernel::class);
class_exists(CacheBridge::class);
class_exists(DoctrineBridge::class);
class_exists(MapperBridge::class);
class_exists(SlimCacheProviderBridge::class);
class_exists(SwaggerBridge::class);
class_exists(TwigBridge::class);
class_exists(ConfigFactory::class);
class_exists(ControllerPool::class);
class_exists(ControllerResolver::class);
class_exists(CorsMiddleware::class);
class_exists(HttpErrorMiddleware::class);
class_exists(ContentTypeMiddleware::class);
class_exists(AppFactory::class);
class_exists(ServerRequestCreatorFactory::class);
class_exists(TwigMiddleware::class);
// phpcs:enable

$configFactory = new ConfigFactory();
$configFactory->importBulk([
    '../config/app.conf',
    '../config/controller.conf',
    '../config/database.conf',
    '../config/route.conf',
    '../config/bridge/cache.conf',
    '../config/bridge/doctrine.conf',
    '../config/bridge/swagger.conf',
    '../config/bridge/twig.conf'
]);

$config = $configFactory->getConfig();
$container = new Container($config);
$request = ServerRequestCreatorFactory::create()
    ->createServerRequestFromGlobals();
$controllerPool = new ControllerPool(
    $container,
    $config,
    new SplObjectStorage()
);

$controllerPool->collect();

AppFactory::setContainer($container);

$app = AppFactory::create();
$controllerResolver = new ControllerResolver($controllerPool, $app);
$kernel = new Kernel($config, $container, $controllerResolver);

// register all required extension, and load it.
$kernel->addExtension(new CacheBridge(), getcwd());
$kernel->addExtension(new DoctrineBridge(), getcwd());
$kernel->addExtension(new MapperBridge(), getcwd());
$kernel->addExtension(new SlimCacheProviderBridge(), getcwd());
$kernel->addExtension(new SwaggerBridge(), getcwd());
$kernel->addExtension(new TwigBridge(), getcwd());
$kernel->load();

// register all required middleware, and resolve
// registered controller from incoming HTTP request.
$controllerResolver->add(TwigMiddleware::create($app, $container->get('twig')));
$controllerResolver->add(new ContentTypeMiddleware($controllerPool));
$controllerResolver->addHttpCache();
//$controllerResolver->add(new RoleMiddleware($controllerPool));
$controllerResolver->addRoutingMiddleware();
$controllerResolver->add(new CorsMiddleware($controllerPool));
$controllerResolver->addBodyParsingMiddleware();
$controllerResolver->add(new HttpErrorMiddleware($controllerPool));
$controllerResolver->resolve($request);

// register shutdown handler (need better registration mechanism)
registerShutdownHandler($request, shutdownHandlerCallback($request));

// serve the request
$kernel->handle($request);
