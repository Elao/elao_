<?php

namespace App\SEOTool\Checker;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class RobotGuidelinesChecker
{
    /** @var Crawler */
    public $crawler;

    /** @var Response */
    public $response;

    public function __construct(Crawler $crawler, Response $response)
    {
        $this->crawler = $crawler;
        $this->response = $response;
    }

    public function getXRobotsTag()
    {
        return $this->response->headers->get('X-Robots-Tag');
    }

    public function getCanonical()
    {
        return $this->crawler->filter('head > link[rel="canonical"]')->attr('href');
    }
}
