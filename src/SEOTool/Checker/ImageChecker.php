<?php

declare(strict_types=1);

namespace App\SEOTool\Checker;

use Symfony\Component\DomCrawler\Crawler;

class ImageChecker
{
    /** @var Crawler */
    private $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function countAltFromImages(): int
    {
        $alt = $this->crawler
            ->filter('img')
            ->extract(['alt']);

        $alt = array_filter($alt);

        return \count($alt);
    }

    public function countAllImages(): int
    {
        $images = $this->crawler
            ->filter('img');

        return \count($images);
    }
}
