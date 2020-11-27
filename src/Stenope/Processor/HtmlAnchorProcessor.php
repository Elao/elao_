<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Stenope\Bundle\Service\HtmlUtils;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Add anchor to titles
 */
class HtmlAnchorProcessor implements ProcessorInterface
{
    private string $property;

    public function __construct(string $property = 'content')
    {
        $this->property = $property;
    }

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!isset($data[$this->property])) {
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
        foreach ($crawler->filter('h1, h2, h3, h4, h5, h6') as $element) {
            $this->addAnchor($element);
        }

        $data[$this->property] = $crawler->html();
    }

    /**
     * Set title id and add anchor
     */
    private function addAnchor(\DOMElement $element): void
    {
        if (!$element->hasAttribute('id')) {
            return;
        }

        HtmlUtils::addClass($element, 'anchor-title');
        HtmlUtils::wrapContent($element, 'a', ['href' => '#' . $element->getAttribute('id')]);
    }
}
