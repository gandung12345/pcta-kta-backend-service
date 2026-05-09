<?php

declare(strict_types=1);

namespace Pcta\Api\Type;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
enum Sex: string
{
    case Male = 'LAKI-LAKI';
    case Female = 'PEREMPUAN';
}
