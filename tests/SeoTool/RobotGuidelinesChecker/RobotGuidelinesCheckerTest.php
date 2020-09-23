<?php

declare(strict_types=1);

use App\SEOTool\Checker\RobotGuidelinesChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class RobotGuidelinesCheckerTest extends TestCase
{
    public function testCanonical()
    {
        $checker = $this->getRobotGuidelinesChecker('with-canonical.html');
        static::assertEquals('http://localhost:8080/', $checker->getCanonical());
    }

    public function testNoCanonical()
    {
        $checker = $this->getRobotGuidelinesChecker('no-canonical.html');
        static::assertNull($checker->getCanonical());
    }

    public function testXRobotsTagNoIndex()
    {
        $checker = new RobotGuidelinesChecker(new Crawler(), new Response('', 200, ['X-Robots-Tag' => 'noindex']));
        static::assertEquals('noindex', $checker->getXRobotsTag());
    }

    public function testXRobotsTagEmpty()
    {
        $checker = new RobotGuidelinesChecker(new Crawler(), new Response('', 200, []));
        static::assertEquals(null, $checker->getXRobotsTag());
    }

    public function getRobotGuidelinesChecker($filename): RobotGuidelinesChecker
    {
        $html = file_get_contents(sprintf('tests/SeoTool/RobotGuidelinesChecker/%s', $filename));
        $crawler = new Crawler($html);

        return new RobotGuidelinesChecker($crawler, new Response());
    }
}
