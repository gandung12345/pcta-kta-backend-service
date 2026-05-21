<?php

declare(strict_types=1);

namespace Pcta\Api\Type;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
enum Role: string
{
    case Administrator = 'ADMINISTRATOR';
    case Regular = 'REGULAR';
}
