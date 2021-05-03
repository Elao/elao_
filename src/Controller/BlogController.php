<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use Stenope\Bundle\ContentManager;
use Stenope\Bundle\Service\ContentUtils;
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
     * @Route(name="blog")
     * @Route("/page/{!page}", name="blog_page", requirements={"page"="\d+"})
     */
    public function index(int $page = 1, int $perPage = 20): Response
    {
        $articles = $this->manager->getContents(Article::class, ['date' => false]);
        $pageArticles = \array_slice($articles, $perPage * ($page - 1), $perPage);

        return $this->render('blog/index.html.twig', [
            'articles' => $pageArticles,
            'page' => $page,
            'minPage' => 1,
            'maxPage' => ceil(\count($articles) / $perPage),
        ])->setLastModified(ContentUtils::max($pageArticles, 'lastModifiedOrCreated'));
    }

    /**
     * @Route("/tag/{tag}", name="blog_tag")
     * @Route("/tag/{tag}/{!page}", name="blog_tag_page", requirements={"page"="\d+"})
     */
    public function tag(string $tag, int $page = 1, int $perPage = 20): Response
    {
        $articles = $this->manager->getContents(
            Article::class,
            ['date' => false],
            fn (Article $article): bool => $article->hasTag($tag)
        );

        $pageArticles = \array_slice($articles, $perPage * ($page - 1), $perPage);

        return $this->render('blog/tag.html.twig', [
            'tag' => $tag,
            'articles' => $pageArticles,
            'page' => $page,
            'minPage' => 1,
            'maxPage' => ceil(\count($articles) / $perPage),
        ])->setLastModified(ContentUtils::max($pageArticles, 'lastModifiedOrCreated'));
    }

    /**
     * @Route("/{article}", name="blog_article", requirements={"article"=".+"})
     */
    public function article(Article $article): Response
    {
        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ])->setLastModified($article->getLastModifiedOrCreated());
    }
}
