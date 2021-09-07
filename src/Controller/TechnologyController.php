<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\CaseStudy;
use App\Model\Technology;
use Stenope\Bundle\ContentManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/glossaire")
 */
class TechnologyController extends AbstractController
{
    private ContentManagerInterface $manager;

    public function __construct(ContentManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/{technology}", name="technology")
     */
    public function show(Technology $technology): Response
    {
        if (!\is_null($technology->articles)) {
            $articles = $this->manager->getContents(
                Article::class,
                ['date' => false],
                fn ($article) => \in_array($article->slug, $technology->articles, true)
            );
        } else {
            $articles = $this->manager->getContents(
                Article::class,
                ['date' => false],
                fn (Article $article): bool => $article->hasTag($technology->slug)
            );
        }

        $caseStudies = $this->manager->getContents(
            CaseStudy::class,
            ['date' => false],
            fn (CaseStudy $caseStudy): bool => $caseStudy->enabled && $caseStudy->hasTechnology($technology)
        );

        return $this->render('technology/technology.html.twig', [
            'technology' => $technology,
            'articles' => \array_slice($articles, 0, 3),
            'caseStudies' => $caseStudies,
        ])->setLastModified($technology->lastModified);
    }
}
