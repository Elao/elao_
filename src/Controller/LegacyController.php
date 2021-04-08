<?php

declare(strict_types=1);

namespace App\Controller;

use App\Legacy\HtaccessGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegacyController extends AbstractController
{
    #[Route('/.htaccess', name: 'htaccess', options: [
        'stenope' => ['ignore' => true],
    ], format: 'htaccess')]
    public function htaccess(HtaccessGenerator $generator): Response
    {
        return new Response($generator->generateHtAccess(HtaccessGenerator::TARGET_SITE));
    }
}
