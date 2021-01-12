<?php

namespace App\Legacy;

use App\Model\Article;
use Stenope\Bundle\ContentManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HtaccessGenerator
{
    private UrlGeneratorInterface $router;
    private ContentManager $contentManager;

    public function __construct(UrlGeneratorInterface $router, ContentManager $contentManager)
    {
        $this->router = $router;
        $this->contentManager = $contentManager;
    }

    public function generateHtAccess(): string
    {
        $rules = [];

        foreach ($this->getStaticPages() as $path => $route) {
            $url = $this->router->generate($route);
            $rules[] = $this->getRule($path, $url);
        }

        $articles = $this->getArticlesBefore(new \DateTimeImmutable('2021-01-01'));

        foreach ($articles as $article) {
            $url = $this->router->generate('blog_article', ['article' => $article->slug]);
            $rules[] = $this->getRule(sprintf('/%s/%s', $article->lang, $article->slug), $url);
        }

        return implode(PHP_EOL, $rules);
    }

    private function getStaticPages(): array
    {
        return [
            '/fr' => 'homepage',
            '/fr/developpement' => 'services',
            '/fr/hebergement' => 'services',
            '/fr/conseil' => 'services',
            '/fr/nos-experiences' => 'case_studies',
            '/fr/recrutement' => 'jobs',
            '/fr/nous-contacter' => 'contact',
        ];
    }

    private function getRule(string $legacyPath, string $url): string
    {
        return sprintf('RedirectPermanent %s %s', $legacyPath, $url);
    }

    private function getArticlesBefore(\DateTimeInterface $limit): array
    {
        return $this->contentManager->getContents(
            Article::class,
            ['date' => false],
            fn($article) => $article->publishdate < $limit
        );
    }
}
