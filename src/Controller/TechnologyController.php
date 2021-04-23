<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\CaseStudy;
use App\Model\Technology;
use Stenope\Bundle\ContentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/techno")
 */
class TechnologyController extends AbstractController
{
    private ContentManager $manager;

    public function __construct(ContentManager $manager)
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
                fn ($article) => $article->hasTag($technology->slug)
            );
        }

        if (!\is_null($technology->caseStudies)) {
            $caseStudies = $this->manager->getContents(
                CaseStudy::class,
                ['date' => false],
                fn ($article) => \in_array($article->slug, $technology->caseStudies, true)
            );
        }

        return $this->render('technology/technology.html.twig', [
            'technology' => $technology,
            'articles' => \array_slice($articles, 0, 3),
            'caseStudies' => $caseStudies ?? [],
        ])->setLastModified($technology->lastModified);
    }
}
