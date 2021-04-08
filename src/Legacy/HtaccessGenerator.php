<?php

declare(strict_types=1);

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
        $redirections = $this->getRedirections();

        return implode(
            PHP_EOL,
            array_map(
                fn ($key) => $this->getHtaccessRule($key, $redirections[$key]),
                array_keys($redirections)
            )
        );
    }

    public function generateNginxRewriteRules(): string
    {
        $redirections = $this->getRedirections();

        return implode(
            PHP_EOL,
            array_map(
                fn ($key) => $this->getNginxRule($key, $redirections[$key]),
                array_keys($redirections)
            )
        );
    }

    private function getRedirections(): array
    {
        $redirections = array_map(fn (string $route) => $this->router->generate($route), [
            '/fr' => 'homepage',
            '/fr/' => 'homepage',
            '/fr/developpement' => 'services',
            '/fr/developpement/' => 'services',
            '/fr/hebergement' => 'services',
            '/fr/hebergement/' => 'services',
            '/fr/conseil' => 'services',
            '/fr/conseil/' => 'services',
            '/fr/nos-experiences' => 'case_studies',
            '/fr/nos-experiences/' => 'case_studies',
            '/fr/recrutement' => 'jobs',
            '/fr/recrutement/' => 'jobs',
            '/fr/nous-contacter' => 'contact',
            '/fr/nous-contacter/' => 'contact',
        ]);

        $articles = $this->getArticlesBefore(new \DateTimeImmutable('2021-01-01'));

        foreach ($articles as $article) {
            $path = sprintf('/%s/%s', $article->lang, $article->slug);
            $url = $this->router->generate('blog_article', ['article' => $article->slug], UrlGeneratorInterface::ABSOLUTE_URL);
            $redirections[$path] = $url;
        }

        return $redirections;
    }

    private function getHtaccessRule(string $legacyPath, string $url): string
    {
        return sprintf('RedirectPermanent %s %s', $legacyPath, $url);
    }

    private function getNginxRule(string $legacyPath, string $url): string
    {
        return sprintf('rewrite ^%s$ %s permanent;', $legacyPath, $url);
    }

    /**
     * @return Article[]
     */
    private function getArticlesBefore(\DateTimeInterface $limit): array
    {
        return $this->contentManager->getContents(
            Article::class,
            ['date' => false],
            fn (Article $article) => $article->publishdate < $limit
        );
    }
}
