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

    public function getHeadlineTree()
    {
        $treeHeadlines = [];
        $headlines = $this->crawler->filter('h1, h2, h3, h4, h5, h6');

        $current = null;

        foreach ($headlines as $element) {
            if (\is_null($current)) {
                $tag = $element->tagName;
                $current = new Headline($tag[1], $element->textContent);
                $treeHeadlines[] = $current;
            } else {
                $tag = $element->tagName;
                $newHeadline = new Headline($tag[1], $element->textContent);

                if ($newHeadline->getLevel() > $current->getLevel()) {
                    $current->addChild($newHeadline);
                    $newHeadline->setParent($current);
                    $current = $newHeadline;
                } elseif (empty($current->parent)) {
                    $treeHeadlines[] = $newHeadline;
                    $current = $newHeadline;
                } else {
                    while (!empty($current->parent)) {
                        if ($newHeadline->getLevel() > $current->parent->getLevel()) {
                            $current->parent->addChild($newHeadline);
                            $newHeadline->setParent($current->parent);
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

    public function countParents($current): int
    {
        $countParents = 0;
        while ($current->parent instanceof Headline) {
            ++$countParents;
            $current = $current->parent;
        }

        return $countParents;
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

    public function isHeaderInArticle()
    {
        return \count($this->crawler->filter('article > header')) >= 1;
    }
}
