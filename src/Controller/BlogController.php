<?php

namespace App\Controller;

use App\Model\Article;
use Content\ContentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index()
    {
        $articles = $this->manager->getContents(Article::class, ['date' => false]);
        $lastModified = max(array_map(fn($article) => $article->lastModified, $articles));

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ])->setLastModified($lastModified);
    }

    /**
     * @Route("/{slug}", name="blog_article")
     */
    public function article(string $slug)
    {
        $article = $this->manager->getContent(Article::class, $slug);

        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ])->setLastModified($article->lastModified);
    }

    /**
     * @Route("/tag/{tag}", name="blog_tag")
     */
    public function tag()
    {
        return $this->render('blog/tag.html.twig');
    }
}
