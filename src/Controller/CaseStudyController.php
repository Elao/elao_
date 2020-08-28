<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etudes-de-cas")
 */
class CaseStudyController extends AbstractController
{
    /**
     * @Route("/", name="case_studies")
     */
    public function index()
    {
        return $this->render('case_study/index.html.twig');
    }

    /**
     * @Route("/{slug}", name="case_study")
     */
    public function caseStudy()
    {
        return $this->render('case_study/show.html.twig');
    }
}
