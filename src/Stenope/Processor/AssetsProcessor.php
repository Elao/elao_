<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Stenope\Bundle\Service\AssetUtils;

/**
 * Attempt to resolve local assets URLs using the Asset component for images and links.
 *
 * These changes could be made upstream (in Stenope)
 */
class AssetsProcessor implements ProcessorInterface
{
    private AssetUtils $assetUtils;
    private HtmlCrawlerManagerInterface $crawlers;
    private string $property;

    public function __construct(AssetUtils $assetUtils, HtmlCrawlerManagerInterface $crawlers, string $property = 'content')
    {
        $this->assetUtils = $assetUtils;
        $this->crawlers = $crawlers;
        $this->property = $property;
    }

    public function __invoke(array &$data, Content $content): void
    {
        if (!isset($data[$this->property])) {
            return;
        }

        $crawler = $this->crawlers->get($content, $data, $this->property);

        if (\is_null($crawler)) {
            return;
        }

        /** @var \DOMElement $element */
        foreach ($crawler->filter('source') as $element) {
            $element->setAttribute('src', $this->assetUtils->getUrl($element->getAttribute('src')));
        }

        /** @var \DOMElement $element */
        foreach ($crawler->filter('video') as $element) {
            if ($element->hasAttribute('src')) {
                $element->setAttribute('src', $this->assetUtils->getUrl($element->getAttribute('src')));
            }
        }

        $this->crawlers->save($content, $data, $this->property);
    }
}
