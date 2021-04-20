<?php

declare(strict_types=1);

namespace App\Legacy;

use App\Model\Article;
use Stenope\Bundle\ContentManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HtaccessGenerator
{
    public const TARGET_SITE = 'site';
    public const TARGET_BLOG = 'blog';

    private UrlGeneratorInterface $router;
    private ContentManager $contentManager;

    public function __construct(UrlGeneratorInterface $router, ContentManager $contentManager)
    {
        $this->router = $router;
        $this->contentManager = $contentManager;
    }

    public function generateHtAccess(string $target): string
    {
        $redirections = $this->getRedirections($target);

        return implode(
            PHP_EOL,
            array_map(
                fn ($key) => $this->getHtaccessRule($key, $redirections[$key]),
                array_keys($redirections)
            )
        );
    }

    public function generateNginxRewriteRules(string $target): string
    {
        $redirections = $this->getRedirections($target);

        return implode(
            PHP_EOL,
            array_map(
                fn ($key) => $this->getNginxRule($key, $redirections[$key]),
                array_keys($redirections)
            )
        );
    }

    private function getRedirections(string $target): array
    {
        $redirections = $target === self::TARGET_SITE ? $this->getSiteRedirections() : $this->getBlogRedirections();

        return array_merge($redirections, $this->getTrailingSlash($redirections));
    }

    private function getTrailingSlash(array $redirections): array
    {
        $trailingSlashRedirection = [];

        foreach ($redirections as $source => $destination) {
            if (preg_match('/\/$/', $source, $matches, PREG_OFFSET_CAPTURE) !== 1) {
                $trailingSlashRedirection[$source . '/'] = $destination;
            }
        }

        return $trailingSlashRedirection;
    }

    private function getBlogRedirections(): array
    {
        $redirections = array_map(fn (string $route) => $this->router->generate($route, [], UrlGeneratorInterface::ABSOLUTE_URL), [
            '/' => 'blog',
            '/fr' => 'blog',
        ]);

        $articles = $this->getArticlesBefore(new \DateTimeImmutable('2021-01-01'));

        foreach ($articles as $article) {
            $path = sprintf('/%s/%s', $article->lang, $article->slug);
            $url = $this->router->generate('blog_article', ['article' => $article->slug], UrlGeneratorInterface::ABSOLUTE_URL);
            $redirections[$path] = $url;

            foreach ($article->tags as $tag) {
                $tagPath = sprintf('/fr/tags/%s', $tag);
                $tagUrl = $this->router->generate('blog_tag', ['tag' => $tag], UrlGeneratorInterface::ABSOLUTE_URL);
                $redirections[$tagPath] = $tagUrl;
            }
        }

        return $redirections;
    }

    private function getSiteRedirections(): array
    {
        return array_map(fn (string $route) => $this->router->generate($route), [
            '/fr' => 'homepage',
            '/fr/la-tribu' => 'team',
            '/fr/developpement' => 'services',
            '/fr/hebergement' => 'services',
            '/fr/conseil' => 'services',
            '/fr/nos-experiences' => 'case_studies',
            '/fr/recrutement' => 'jobs',
            '/fr/nous-contacter' => 'contact',
            '/fr/legal' => 'legal',
            '/fr/a-propos' => 'contact',
        ]);
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
