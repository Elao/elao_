<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('site/index.html.twig');
    }

    /**
     * @Route("/nos-services", name="services")
     */
    public function services()
    {
        return $this->render('site/services.html.twig');
    }

    /**
     * @Route("/methodo", name="methodology")
     */
    public function methodology()
    {
        return $this->render('site/methodology.html.twig');
    }

    /**
     * @Route("/nos-valeurs", name="values")
     */
    public function values()
    {
        return $this->render('site/values.html.twig');
    }

    /**
     * @Route("/nos-technos", name="technologies")
     */
    public function technologies()
    {
        return $this->render('site/technologies.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('site/contact.html.twig');
    }
}
