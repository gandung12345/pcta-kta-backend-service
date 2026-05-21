<?php

declare(strict_types=1);

namespace Schnell\Bridge\Doctrine\Type;

use DateTime;
use Override;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Schnell\Decorator\Stringified\DateTimeDecorator;

use function class_exists;

// help opcache.preload discover always-needed symbols
// phpcs:disable
class_exists(DateTime::class);
class_exists(Override::class);
class_exists(AbstractPlatform::class);
class_exists(Type::class);
class_exists(DateTimeDecorator::class);
// phpcs:enable

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
class DateTimeType extends Type
{
    /**
     * {@inheritdoc}
     */
    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDateTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value
            ->withFormat($platform->getDateFormatString())
            ->stringify();
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTime
    {
        if (null === $value || $value instanceof DateTimeDecorator) {
            return $value;
        }

        return new DateTimeDecorator($value, $platform->getDateFormatString());
    }

    /**
     * @psalm-api
     *
     * @return string
     */
    public function getName(): string
    {
        return 'date';
    }
}
