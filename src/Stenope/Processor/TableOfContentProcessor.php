<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Build the article table of content from its titles
 */
class TableOfContentProcessor implements ProcessorInterface
{
    public const MAX_DEPTH = 6;

    private string $tableOfContentProperty;
    private string $contentProperty;

    public function __construct(
        string $tableOfContentProperty = 'tableOfContent',
        string $contentProperty = 'content'
    ) {
        $this->tableOfContentProperty = $tableOfContentProperty;
        $this->contentProperty = $contentProperty;
    }

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!isset($data[$this->tableOfContentProperty]) || !$data[$this->tableOfContentProperty]) {
            return;
        }

        $crawler = new Crawler($data[$this->contentProperty]);

        try {
            $crawler->html();
        } catch (\Exception $e) {
            // Content is not valid HTML.
            return;
        }

        $value = $data[$this->tableOfContentProperty];
        $depth = \is_int($value) ? min($value, static::MAX_DEPTH) : static::MAX_DEPTH;
        $titles = implode(', ', array_map(fn ($index) => 'h' . ($index + 1), array_keys(array_fill(0, $depth, null))));
        $tableOfContent = [];

        /** @var \DomElement $element * */
        foreach ($crawler->filter($titles) as $element) {
            $tableOfContent[$element->getAttribute('id')] = $element->textContent;
        }

        $data[$this->tableOfContentProperty] = $tableOfContent;
    }
}
