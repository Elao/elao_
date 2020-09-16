<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use Content\ContentManager;
use Content\Service\ContentUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private ContentManager $manager;

    public function __construct(ContentManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="blog")
     */
    public function index(): Response
    {
        $articles = $this->manager->getContents(Article::class, ['date' => false]);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ])->setLastModified(ContentUtils::max($articles, 'lastModified'));
    }

    /**
     * @Route("/tag/{tag}", name="blog_tag")
     */
    public function tag(string $tag): Response
    {
        $articles = $this->manager->getContents(
            Article::class,
            ['date' => false],
            fn ($article) => $article->hasTag($tag)
        );

        return $this->render('blog/tag.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
        ])->setLastModified(ContentUtils::max($articles, 'lastModified'));
    }

    /**
     * @Route("/{article}", name="blog_article", requirements={"article"=".+"})
     */
    public function article(Article $article): Response
    {
        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ])->setLastModified($article->lastModified);
    }
}
