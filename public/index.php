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
use Schnell\Config\ConfigFactory;
use Schnell\Config\ConfigInterface;
use Schnell\Controller\ControllerPool;
use Schnell\Controller\ControllerResolver;
use Schnell\Middleware\CorsMiddleware;
use Schnell\Middleware\HttpErrorMiddleware;
use Schnell\Middleware\ContentTypeMiddleware;

use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

use Odan\Twig\TwigAssetsExtension;

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
class_exists(ConfigFactory::class);
class_exists(ControllerPool::class);
class_exists(ControllerResolver::class);
class_exists(CorsMiddleware::class);
class_exists(HttpErrorMiddleware::class);
class_exists(ContentTypeMiddleware::class);
class_exists(AppFactory::class);
class_exists(ServerRequestCreatorFactory::class);
class_exists(Twig::class);
class_exists(TwigMiddleware::class);
class_exists(TwigAssetsExtension::class);
// phpcs:enable

$configFactory = new ConfigFactory();
$configFactory->importBulk([
    '../config/app.conf',
    '../config/controller.conf',
    '../config/database.conf',
    '../config/route.conf',
    '../config/bridge/cache.conf',
    '../config/bridge/doctrine.conf',
    '../config/bridge/swagger.conf'
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

/*
 * this is an exception for twig slim extension.
 * it must be loaded first before activating the middleware,
 * no need to register that first to the extension bridging mechanism.
 */
$container->set(Twig::class, function (ContainerInterface $container, ConfigInterface $config): Twig {
    $twig = Twig::create(
        __DIR__ . DIRECTORY_SEPARATOR . 'templates',
        ['cache' => '../var/twig-cache', 'auto_reload' => true]
    );

    // activate asset extension
    $twig->addExtension(new TwigAssetsExtension($twig->getEnvironment(), [
        'path' => __DIR__ . DIRECTORY_SEPARATOR . 'templates/assets/cache',
        'url_base_path' => 'templates/assets/cache/',
        'cache_path' => '../var/twig-assets-cache',
        'cache_name' => 'twig-assets-cache',
        'cache_lifetime' => 0,
        'minify' => 0
    ]));

    return $twig;
});

/* set it's alias too */
$container->alias(Twig::class, 'twig');

$controllerResolver = new ControllerResolver($controllerPool, $app);
$controllerResolver->add(TwigMiddleware::create($app, $container->get('twig')));
$controllerResolver->add(new ContentTypeMiddleware($controllerPool));
$controllerResolver->addHttpCache();
//$controllerResolver->add(new RoleMiddleware($controllerPool));
$controllerResolver->addRoutingMiddleware();
$controllerResolver->add(new CorsMiddleware($controllerPool));
$controllerResolver->addBodyParsingMiddleware();
$controllerResolver->add(new HttpErrorMiddleware($controllerPool));
$controllerResolver->resolve($request);

registerShutdownHandler($request, shutdownHandlerCallback($request));

$kernel = new Kernel($config, $container, $controllerResolver);
$kernel->addExtension(new CacheBridge(), getcwd());
$kernel->addExtension(new DoctrineBridge(), getcwd());
$kernel->addExtension(new MapperBridge(), getcwd());
$kernel->addExtension(new SlimCacheProviderBridge(), getcwd());
$kernel->addExtension(new SwaggerBridge(), getcwd());
$kernel->load();
$kernel->handle($request);
