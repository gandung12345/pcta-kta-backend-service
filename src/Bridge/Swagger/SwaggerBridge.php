<?php

declare(strict_types=1);

namespace Schnell\Bridge\Swagger;

use Override;
use OpenApi\Annotations\OpenApi;
use OpenApi\Generator as OpenApiGenerator;
use Psr\Log\NullLogger;
use Schnell\ContainerInterface;
use Schnell\Bridge\AbstractBridge;
use Schnell\Config\ConfigInterface;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(Override::class);
class_exists(OpenApi::class);
class_exists(OpenApiGenerator::class);
class_exists(NullLogger::class);
class_exists(AbstractBridge::class);
// phpcs:enable

/**
 * @psalm-api
 *
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class SwaggerBridge extends AbstractBridge
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function load(): void
    {
        $container = $this->getContainer();

        /** @psalm-suppress PossiblyNullReference */
        $container->set(
            OpenApi::class,
            function (ContainerInterface $container, ConfigInterface $config): ?OpenApi {
                /** @psalm-suppress PossiblyNullArgument */
                $normalizedDirs = array_map(
                    fn(string $dir) => sprintf(
                        '%s%s%s',
                        $this->getBasePath(),
                        DIRECTORY_SEPARATOR,
                        $dir
                    ),
                    $config->get('swagger.dirs')
                );

                /** @psalm-ignore-nullable-return */
                return OpenApiGenerator::scan($normalizedDirs, [
                    'logger' => new NullLogger()
                ]);
            }
        );

        /** @psalm-suppress PossiblyNullReference */
        $container->alias(OpenApi::class, $this->getAlias());
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getAlias(): string
    {
        return 'swagger';
    }
}
