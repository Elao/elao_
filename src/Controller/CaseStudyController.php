<?php

namespace App\Controller;

use App\Model\CaseStudy;
use Content\ContentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etudes-de-cas")
 */
class CaseStudyController extends AbstractController
{
    private ContentManager $manager;

    public function __construct(ContentManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="case_studies")
     */
    public function index()
    {
        $caseStudies = $this->manager->getContents(CaseStudy::class, ['date' => false]);
        $lastModified = max(array_map(fn($caseStudy) => $caseStudy->lastModified, $caseStudies));

        return $this->render('case_study/index.html.twig', [
            'caseStudies' => $caseStudies,
        ])->setLastModified($lastModified);
    }

    /**
     * @Route("/{slug}", name="case_study")
     */
    public function caseStudy(string $slug)
    {
        $caseStudy = $this->manager->getContent(CaseStudy::class, $slug);

        return $this->render('case_study/show.html.twig', [
            'caseStudy' => $caseStudy,
        ])->setLastModified($caseStudy->lastModified);
    }
}
