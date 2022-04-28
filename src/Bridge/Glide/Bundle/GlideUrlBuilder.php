<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle;

use League\Glide\Urls\UrlBuilderFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GlideUrlBuilder
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private string $basePath,
        private ?string $signKey = null,
    ) {
    }

    public function buildUrl(string $filename, array $params = []): string
    {
        return UrlBuilderFactory::create($this->basePath, $this->signKey)->getUrl($filename, $params);
    }

    public function getPublicCachePath(string $cachePath): string
    {
        return $this->urlGenerator->generate('glide_image_resized_url', ['path' => $cachePath]);
    }
}
