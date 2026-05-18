<?php

declare(strict_types=1);

namespace Pcta\Api\Controller;

use OpenApi\Attributes as OpenApi;
use Schnell\Controller\AbstractController;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
#[OpenApi\Info(
    title: 'PCTA Member Registration REST API',
    version: '1.0-dev'
)]
class BaseController extends AbstractController
{
}
