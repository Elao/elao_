<?php

declare(strict_types=1);

namespace App\SEOTool\Checker;

use Symfony\Component\DomCrawler\Crawler;

class AccessibilityChecker
{
    const MAX_LEVEL_HEADLINES = 6;

    /** @var Crawler */
    private $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function countHeadlinesByHn(): array
    {
        $i = 1;
        $headings = [];
        while ($i <= 6):
            try {
                $headings[sprintf('h%d', $i)] = \count($this->crawler->filter(sprintf('h%d', $i)));
            } catch (\Exception $e) {
            }
        ++$i;
        endwhile;

        return $headings;
    }

    public function getHeadlineTree(): array
    {
        $treeHeadlines = [];

        /** @var \IteratorIterator $headlines */
        $headlines = $this->crawler->filter('h1, h2, h3, h4, h5, h6');

        $current = null;

        foreach ($headlines as $element) {
            /* @var \DOMElement $element */
            if (\is_null($current)) {
                $tag = $element->tagName;
                $current = new Headline((int) $tag[1], $element->textContent);
                $treeHeadlines[] = $current;
            } else {
                $tag = $element->tagName;
                $newHeadline = new Headline((int) $tag[1], $element->textContent);

                if ($newHeadline->getLevel() > $current->getLevel()) {
                    $current->addChild($newHeadline);
                    $newHeadline->setParent($current);
                    $current = $newHeadline;
                } elseif (!$current->isParent()) {
                    $treeHeadlines[] = $newHeadline;
                    $current = $newHeadline;
                } elseif ($current->getParent() !== null) {
                    while ($current->getParent() !== null) {
                        /** @var Headline $parentOfCurrent */
                        $parentOfCurrent = $current->getParent();
                        if ($newHeadline->getLevel() > $parentOfCurrent->getLevel()) {
                            $parentOfCurrent->addChild($newHeadline);
                            $newHeadline->setParent($parentOfCurrent);
                            $current = $newHeadline;
                            break;
                        }
                        $current = $current->parent;
                    }
                    if ($current !== $newHeadline) {
                        $treeHeadlines[] = $newHeadline;
                        $current = $newHeadline;
                    }
                }
            }
        }

        return $treeHeadlines;
    }

    public function isHeader(): bool
    {
        return \count($this->crawler->filter('body > header')) >= 1;
    }

    public function isAside(): bool
    {
        return \count($this->crawler->filter('aside')) >= 1;
    }

    public function isNavInHeader(): bool
    {
        return \count($this->crawler->filter('header > nav')) >= 1;
    }

    public function isFooter(): bool
    {
        return \count($this->crawler->filter('body > footer')) >= 1;
    }

    public function isArticle(): bool
    {
        return \count($this->crawler->filter('article')) >= 1;
    }

    public function isHeaderInArticle(): bool
    {
        return \count($this->crawler->filter('article > header')) >= 1;
    }
}
