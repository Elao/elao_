<?php

declare(strict_types=1);

namespace App\Controller;

use App\Glossary\GlossaryBuilder;
use App\Model\Article;
use App\Model\CaseStudy;
use App\Model\Glossary\Term;
use Stenope\Bundle\ContentManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/glossaire')]
class GlossaryController extends AbstractController
{
    private ContentManagerInterface $manager;

    public function __construct(ContentManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/', name: 'glossary')]
    public function glossary(GlossaryBuilder $builder): Response
    {
        $terms = $this->manager->getContents(Term::class, 'name', ['show' => true]);

        return $this->render('glossary/index.html.twig', [
            'glossary' => $builder->build($terms),
        ]);
    }

    #[Route('/{term}', name: 'glossary_term')]
    public function show(Term $term): Response
    {
        if (!\is_null($term->articles)) {
            $articles = $this->manager->getContents(
                Article::class,
                ['date' => false],
                fn ($article) => \in_array($article->slug, $term->articles, true)
            );
        } else {
            $articles = $this->manager->getContents(
                Article::class,
                ['date' => false],
                fn (Article $article): bool => $article->hasTag($term->slug)
            );
        }

        $caseStudies = $this->manager->getContents(
            CaseStudy::class,
            ['date' => false],
            fn (CaseStudy $caseStudy): bool => $caseStudy->enabled && $caseStudy->hasTerm($term)
        );

        return $this->render('glossary/term.html.twig', [
            'term' => $term,
            'articles' => \array_slice($articles, 0, 3),
            'caseStudies' => $caseStudies,
        ])->setLastModified($term->lastModified);
    }
}
