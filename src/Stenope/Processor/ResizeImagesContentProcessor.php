<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Bridge\Glide\Bundle\ResizedUrlGenerator;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Provide resized and optimized images, for retina devices as well, inside a specific content.
 */
class ResizeImagesContentProcessor implements ProcessorInterface
{
    private ResizedUrlGenerator $resizedUrlGenerator;

    private string $type;
    private string $preset;
    private string $property;

    public function __construct(
        ResizedUrlGenerator $resizedUrlGenerator,
        string $type,
        string $preset,
        string $property = 'content'
    ) {
        $this->resizedUrlGenerator = $resizedUrlGenerator;
        $this->type = $type;
        $this->preset = $preset;
        $this->property = $property;
    }

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if ($type !== $this->type) {
            return;
        }

        $crawler = new Crawler($data[$this->property]);

        try {
            $crawler->html();
        } catch (\Exception $e) {
            // Content is not valid HTML.
            return;
        }

        $crawler = new Crawler($data[$this->property]);

        /** @var \DomElement $element * */
        foreach ($crawler->filter('img') as $element) {
            $this->processImage($element);
        }

        $data[$this->property] = $crawler->html();
    }

    private function processImage(\DOMElement $element): void
    {
        if (!$element->hasAttribute('src')) {
            return;
        }

        $source = $element->getAttribute('src');

        if (!$this->isLocalImage($source)) {
            return;
        }

        // Ignore animated gifs
        if (preg_match('/\.gif$/', $source)) {
            return;
        }

        $dpr1 = $this->resizedUrlGenerator->withPreset($source, $this->preset);
        $dpr2 = $this->resizedUrlGenerator->withPreset($source, $this->preset, ['dpr' => 2]);

        $element->setAttribute('src', $dpr1);
        $element->setAttribute('srcset', <<<HTML
        $dpr1 1x,
        $dpr2 2x,
        HTML);
    }

    private function isLocalImage(string $url): bool
    {
        return !\is_string(parse_url($url, PHP_URL_HOST));
    }
}
