<?php

declare(strict_types=1);

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

    public function getXRobotsTag(): ?string
    {
        try {
            return $this->response->headers->get('X-Robots-Tag');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCanonical(): ?string
    {
        try {
            return $this->crawler->filter('head > link[rel="canonical"]')->attr('href');
        } catch (\Exception $e) {
            return null;
        }
    }
}
