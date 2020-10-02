<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\CaseStudy;
use Content\ContentManager;
use Content\Service\ContentUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function list(): Response
    {
        $caseStudies = $this->manager->getContents(CaseStudy::class, ['date' => false]);

        return $this->render('case_study/index.html.twig', [
            'caseStudies' => $caseStudies,
        ])->setLastModified(ContentUtils::max($caseStudies, 'lastModified'));
    }

    /**
     * @Route("/{slug}", name="case_study")
     */
    public function show(string $slug): Response
    {
        /** @var CaseStudy $caseStudy */
        $caseStudy = $this->manager->getContent(CaseStudy::class, $slug);

        return $this->render('case_study/show.html.twig', [
            'caseStudy' => $caseStudy,
        ])->setLastModified($caseStudy->lastModified);
    }
}
