<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle;

use League\Glide\Urls\UrlBuilderFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GlideUrlBuilder
{
    private UrlGeneratorInterface $urlGenerator;
    private string $basePath;
    private ?string $signKey;

    public function __construct(UrlGeneratorInterface $urlGenerator, string $basePath, ?string $signKey = null)
    {
        $this->basePath = $basePath;
        $this->signKey = $signKey;
        $this->urlGenerator = $urlGenerator;
    }

    public function buildUrl(string $filename, array $params = []): string
    {
        $urlBuilder = UrlBuilderFactory::create($this->basePath, $this->signKey);

        return $urlBuilder->getUrl($filename, $params);
    }

    public function getPublicCachePath(string $cachePath): string
    {
        return $this->urlGenerator->generate('glide_image_resized_url', ['path' => $cachePath]);
    }
}
