<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nos-services')]
class ServicesController extends AbstractController
{
    #[Route('/', name: 'services')]
    public function services(): Response
    {
        return $this->render('site/services/services.html.twig');
    }

    #[Route('/ia', name: 'ia', options: [
        'stenope' => [
            'sitemap' => true,
        ],
    ])]
    public function iaServices(): Response
    {
        return $this->render('site/services/ia.html.twig');
    }

    #[Route('/ia/brief-ia/', name: 'ia-brief')]
    public function iaBrief(): Response
    {
        return $this->render('site/services/ia-brief.html.twig');
    }

    #[Route('/application-web-et-mobile', name: 'application')]
    public function servicesApplication(): Response
    {
        return $this->render('site/services/application.html.twig');
    }

    #[Route('/hebergement', name: 'hosting')]
    public function hosting(): Response
    {
        return $this->render('site/services/hosting.html.twig');
    }

    #[Route('/conseil-et-accompagnement', name: 'consulting')]
    public function servicesAdvice(): Response
    {
        return $this->render('site/services/consulting.html.twig');
    }
}
