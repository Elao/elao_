<?php

declare(strict_types=1);

namespace App\SEOTool\Checker;

use Symfony\Component\DomCrawler\Crawler;

class BrokenLinksChecker
{
    /** @var Crawler */
    private $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

//
//    public function getBrokenLinks()
//    {
//        $links = $this->crawler->filter('a')->links();
//    }
}
