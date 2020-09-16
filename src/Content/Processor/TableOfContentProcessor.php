<?php

declare(strict_types=1);

/*
 * This file is part of the "Tom32i/Content" bundle.
 *
 * @author Thomas Jarrand <thomas.jarrand@gmail.com>
 */

namespace App\Content\Processor;

use App\Model\Article;
use Content\Behaviour\ProcessorInterface;
use Content\Content;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Build the article table of content from its titles
 */
class TableOfContentProcessor implements ProcessorInterface
{
    public const MAX_DEPTH = 5;

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

        foreach ($crawler->filter($titles) as $element) {
            $this->register($tableOfContent, $element);
        }

        $data[$this->tableOfContentProperty] = $tableOfContent;
    }

    private function register(array &$tableOfContent, \DOMElement $element): void
    {
        $tableOfContent[$element->getAttribute('id')] = $element->textContent;
    }
}
