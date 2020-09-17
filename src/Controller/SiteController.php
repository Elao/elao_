<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('site/index.html.twig');
    }

    /**
     * @Route("/nos-services", name="services")
     */
    public function services(): Response
    {
        return $this->render('site/services.html.twig');
    }

    /**
     * @Route("/methodo", name="methodology")
     */
    public function methodology(): Response
    {
        return $this->render('site/methodology.html.twig');
    }

    /**
     * @Route("/nos-valeurs", name="values")
     */
    public function values(): Response
    {
        return $this->render('site/values.html.twig');
    }

    /**
     * @Route("/nos-technos", name="technologies")
     */
    public function technologies(): Response
    {
        return $this->render('site/technologies.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig');
    }
}
