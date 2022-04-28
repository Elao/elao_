<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Bridge\Glide\Bundle\ResizedUrlGenerator;
use App\Bridge\Glide\Bundle\SkippedTypes;
use App\Bridge\Glide\Bundle\SkippingMimeTypesApi;
use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Stenope\Bundle\Provider\Factory\LocalFilesystemProviderFactory;
use Symfony\Component\Filesystem\Path;

/**
 * Provide resized and optimized images, for retina devices as well, inside a specific content.
 */
class ResizeImagesContentProcessor implements ProcessorInterface
{
    public function __construct(
        private ResizedUrlGenerator $resizedUrlGenerator,
        private HtmlCrawlerManagerInterface $crawlers,
        private SkippedTypes $skippedTypes,
        private string $projectDir,
        private string $type,
        private string $preset,
        private string $property = 'content'
    ) {}

    public function __invoke(array &$data, Content $content): void
    {
        if ($content->getType() !== $this->type) {
            return;
        }

        $crawler = $this->crawlers->get($content, $data, $this->property);

        if (\is_null($crawler)) {
            // Content is not valid HTML.
            return;
        }

        /** @var \DOMElement $element */
        foreach ($crawler->filter('img') as $element) {
            $this->processImage($element, $content);
        }

        /** @var \DOMElement $element */
        foreach ($crawler->filter('video') as $element) {
            $this->processVideoPoster($element, $content);
        }

        $this->crawlers->save($content, $data, $this->property);
    }

    private function processImage(\DOMElement $element, Content $content): void
    {
        if (!$element->hasAttribute('src')) {
            return;
        }

        $source = $element->getAttribute('src');

        if (!$this->isLocalImage($source)) {
            return;
        }

        if (!$this->isSupportedImage($source)) {
            $this->processUnsupportedImage($element, $source);

            return;
        }

        $source = $this->normalizePath($source, $content);

        $dpr1 = $this->resizedUrlGenerator->withPreset($source, $this->preset);
        $dpr2 = $this->resizedUrlGenerator->withPreset($source, $this->preset, ['dpr' => 2]);

        $element->setAttribute('src', $dpr1);
        $element->setAttribute('srcset', <<<HTML
        $dpr1 1x,
        $dpr2 2x,
        HTML);
    }

    private function processVideoPoster(\DOMElement $element, Content $content): void
    {
        if (!$element->hasAttribute('poster')) {
            return;
        }

        $source = $element->getAttribute('poster');

        if (!$this->isLocalImage($source)) {
            return;
        }

        if (!$this->isSupportedImage($source)) {
            $this->processUnsupportedImage($element, $source);

            return;
        }

        $source = $this->normalizePath($source, $content);

        $resized = $this->resizedUrlGenerator->withPreset($source, $this->preset);

        $element->setAttribute('poster', $resized);
    }

    /**
     * In case the image is not supported, we'll still generate an URL with Glide, but with no preset/options:
     */
    private function processUnsupportedImage(\DOMElement $element, string $source): void
    {
        $element->setAttribute('src', $this->resizedUrlGenerator->withOptions($source, []));
    }

    private function isLocalImage(string $url): bool
    {
        return !\is_string(parse_url($url, PHP_URL_HOST));
    }

    /**
     * Normalizes the path to be relative to the Glide source directory, i.e project root dir.
     */
    private function normalizePath(string $imgPath, Content $content): string
    {
        // Ignore of not attempting to resolve an image path relative to current content
        if (!$this->isRelativeImagePath($imgPath)) {
            return $imgPath;
        }

        // Ignore if not using the file provider
        if (!$this->isFilesystemProvider($content)) {
            return $imgPath;
        }

        $currentContentPath = $content->getMetadata()['path'];

        return Path::makeRelative(
            // Resolve path relative to current content as absolute full path:
            Path::makeAbsolute($imgPath, Path::getDirectory($currentContentPath)),
            // And return it relative to the project dir:
            $this->projectDir,
        );
    }

    private function isRelativeImagePath(string $imgPath): bool
    {
        return str_starts_with($imgPath, './') || str_starts_with($imgPath, '../');
    }

    private function isFilesystemProvider(Content $content): bool
    {
        return LocalFilesystemProviderFactory::TYPE === ($content->getMetadata()['provider'] ?? null);
    }

    /**
     * Is the image supported for resize.
     *
     * @see SkippingMimeTypesApi
     */
    private function isSupportedImage(string $src): bool
    {
        return !$this->skippedTypes->isSkippedUrl($src);
    }
}
