<?php

declare(strict_types=1);

namespace Pcta\Api\Type;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
enum Religion: string
{
    case Moslem = 'ISLAM';
    case Christian = 'KRISTEN PROTESTAN';
    case Catholic = 'KATOLIK';
    case Hindu = 'HINDU';
    case Buddha = 'BUDDHA';
    case Confucian = 'KONGHUCU';
    case Other = 'PENGHAYAT IMAN & KEPERCAYAAN';
}
