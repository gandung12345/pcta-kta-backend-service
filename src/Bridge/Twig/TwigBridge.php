<?php

declare(strict_types=1);

namespace Schnell\Bridge\Twig;

use Odan\Twig\TwigAssetsExtension;
use Schnell\ContainerInterface;
use Schnell\Bridge\AbstractBridge;
use Schnell\Config\ConfigInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class TwigBridge extends AbstractBridge
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function load(): void
    {
        $container = $this->getContainer();
        $container->set(Twig::class, function (ContainerInterface $container, ConfigInterface $config): Twig {
            $twig = Twig::create(
                $config->get('twig.templateDir'),
                [
                    'cache' => $config->get('twig.templateCacheDir'),
                    'auto_reload' => true
                ]
            );

            // activate twig asset extension on this
            // twig instance.
            $twig->addExtension(new TwigAssetsExtension($twig->getEnvironment(), [
                'path' => $config->get('twig-asset.assetCompiledPath'),
                'url_base_path' => $config->get('twig-asset.assetUrlBasePath'),
                'cache_path' => $config->get('twig-asset.assetRuntimeCachePath'),
                'cache_name' => $config->get('twig-asset.assetRuntimeCacheName'),
                'cache_lifetime' => $config->get('twig-asset.assetRuntimeCacheLifetime'),
                'minify' => $config->get('twig-asset.assetMinify')
            ]));

            return $twig;
        });

        $container->alias(Twig::class, $this->getAlias());
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getAlias(): string
    {
        return 'twig';
    }
}
