<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use Stenope\Bundle\ContentManagerInterface;
use Stenope\Bundle\Exception\ContentNotFoundException;
use Stenope\Bundle\Service\ContentUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    private ContentManagerInterface $manager;

    public function __construct(ContentManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route(name: 'blog')]
    #[Route('/page/{!page}', name: 'blog_page', requirements: ['page' => '\d+'])]
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

    #[Route('/tag/{tag}', name: 'blog_tag')]
    #[Route('/tag/{tag}/{!page}', name: 'blog_tag_page', requirements: ['page' => '\d+'])]
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

    #[Route('/rss.xml', name: 'blog_rss', options: [
        'stenope' => ['sitemap' => false],
    ])]
    public function rss(): Response
    {
        $articles = $this->manager->getContents(Article::class, ['date' => false], '_.date > date("-6 months")');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/xml; charset=UTF-8');

        return $this->render('blog/rss.xml.twig', [
            'articles' => $articles,
        ], $response);
    }

    /**
     * Attempt to render an article by using {@link self::renderArticle()} if a match is found.
     * Otherwise, will attempt to list articles from the same parent path.
     *
     * These routes have lower priority than other ones, since it's almost a catch-all route.
     */
    #[Route('/{path}/{!page}', name: 'blog_articles_from_path_page', requirements: ['page' => '\d+'], priority: -100)]
    #[Route('/{path}', name: 'blog_articles_from_path', requirements: ['path' => '.+'], priority: -100)]
    public function articlesFromPath(Request $request, string $path, int $page = 1, int $perPage = 20): Response
    {
        try {
            // Attempt to find a matching article:
            $article = $this->manager->getContent(Article::class, $path);
            $request->attributes->set('_route', 'blog_article');

            // and redirect to it if found:
            return $this->renderArticle($article);
        } catch (ContentNotFoundException $ex) {
            // If no article matches, continue and try to list articles for the matching path
        }

        $path = rtrim($path, '/');

        $articles = $this->manager->getContents(
            Article::class,
            ['date' => false],
            "_.slug starts with \"$path/\"",
        );

        $pageArticles = \array_slice($articles, $perPage * ($page - 1), $perPage);

        if (\count($pageArticles) === 0) {
            throw $this->createNotFoundException("No articles found for path \"$path\"", $ex);
        }

        return $this->render('blog/articlesFromPath.html.twig', [
            'path' => $path,
            'articles' => $pageArticles,
            'page' => $page,
            'minPage' => 1,
            'maxPage' => ceil(\count($articles) / $perPage),
        ])->setLastModified(ContentUtils::max($pageArticles, 'lastModifiedOrCreated'));
    }

    /**
     * If {@link self::articlesFromPath()} found a match,
     * it'll execute {@link self::renderArticle()} instead of this action to render the article.
     */
    #[Route('/{article}', name: 'blog_article', requirements: ['article' => '.+'], priority: -100)]
    public function article(): void
    {
        // noop, this action is only used for url generation
        throw new \LogicException('This action should never be reached');
    }

    private function renderArticle(Article $article): Response
    {
        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ])->setLastModified($article->getLastModifiedOrCreated());
    }
}
