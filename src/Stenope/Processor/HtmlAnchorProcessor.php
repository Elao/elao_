<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Stenope\Bundle\Service\HtmlUtils;

/**
 * Add anchor to titles by wrapping its content in <a> tags rather than appending it to titles
 * on contrary of {@see \Stenope\Bundle\Processor\HtmlAnchorProcessor}
 */
class HtmlAnchorProcessor implements ProcessorInterface
{
    private HtmlCrawlerManagerInterface $crawlers;
    private string $property;

    public function __construct(HtmlCrawlerManagerInterface $crawlers, string $property = 'content')
    {
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
            // Content is not valid HTML.
            return;
        }

        /** @var \DOMElement $element * */
        foreach ($crawler->filter('h1, h2, h3, h4, h5, h6') as $element) {
            $this->addAnchor($element);
        }

        $this->crawlers->save($content, $data, $this->property);
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
