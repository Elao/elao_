<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\CaseStudy;
use Stenope\Bundle\ContentManager;
use Stenope\Bundle\Service\ContentUtils;
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
     * @Route(name="case_studies")
     */
    public function list(): Response
    {
        $caseStudies = $this->getActiveCasesStudies();

        return $this->render('case_study/index.html.twig', [
            'caseStudies' => $caseStudies,
        ])->setLastModified(ContentUtils::max($caseStudies, 'lastModified'));
    }

    /**
     * @Route("/{caseStudy}", name="case_study")
     */
    public function show(CaseStudy $caseStudy): Response
    {
        $caseStudies = array_filter($this->getActiveCasesStudies(), static fn (CaseStudy $s): bool => $s !== $caseStudy);
        shuffle($caseStudies);

        return $this->render('case_study/show.html.twig', [
            'caseStudy' => $caseStudy,
            'randomStudies' => \array_slice($caseStudies, 0, 2),
        ])->setLastModified($caseStudy->lastModified);
    }

    /**
     * @return CaseStudy[]
     */
    private function getActiveCasesStudies(): array
    {
        return $this->manager->getContents(CaseStudy::class, ['date' => false], ['enabled' => true]);
    }
}
