<?php

declare(strict_types=1);

namespace App\tests\SeoTool\OptimizationChecker;

use App\SEOTool\Checker\OptimizationChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class OptimizationCheckerTest extends TestCase
{
    public function testNoH1()
    {
        $optimizationChecker = $this->getOptimizationChecker('no-h1.html');

        static::assertNull($optimizationChecker->getH1());
    }

    public function testEmptyH1()
    {
        $optimizationChecker = $this->getOptimizationChecker('empty-h1.html');

        static::assertNull($optimizationChecker->getH1());
    }

    public function testNoTitle()
    {
        $optimizationChecker = $this->getOptimizationChecker('no-title.html');

        static::assertNull($optimizationChecker->getTitle());
    }

    public function testNoOpenGraph()
    {
        $optimizationChecker = $this->getOptimizationChecker('no-open-graph.html');

        static::assertEquals('missing', $optimizationChecker->getOpenGraphLevel());
    }

    public function testNoTwitterProperties()
    {
        $optimizationChecker = $this->getOptimizationChecker('no-twitter-properties.html');

        static::assertEquals('missing', $optimizationChecker->getTwitterPropertiesLevel());
    }

    public function testAllIsComplete()
    {
        $optimizationChecker = $this->getOptimizationChecker('all-is-complete.html');

        $twitterExpected = [
            'card' => 'summary',
            'title' => 'Twitter Title',
            'description' => 'Twitter Description',
            'site' => 'Twitter Site',
            'creator' => 'Twitter Creator',
        ];

        $openGraphExpected = [
            'title' => 'Title',
            'locale' => 'fr',
            'description' => 'description',
            'url' => 'http://localhost:8080/',
            'site_name' => 'name',
        ];

        static::assertEquals('This is Title', $optimizationChecker->getTitle());
        static::assertEquals('This is meta description', $optimizationChecker->getMetaDescription());
        static::assertEquals('This is H1', $optimizationChecker->getH1());
        static::assertEquals('completed', $optimizationChecker->getTwitterPropertiesLevel());
        static::assertEquals('completed', $optimizationChecker->getOpenGraphLevel());
        static::assertIsArray($optimizationChecker->getTwitterProperties());
        static::assertEquals($twitterExpected, $optimizationChecker->getTwitterProperties());
        static::assertEquals($openGraphExpected, $optimizationChecker->getOpenGraphProperties());
    }

    public function testOgIsNotComplete()
    {
        $optimizationChecker = $this->getOptimizationChecker('og-properties-missing.html');

        $openGraphExpected = [
            'url' => 'http://localhost:8080/',
            'site_name' => 'name',
        ];

        static::assertEquals($openGraphExpected, $optimizationChecker->getOpenGraphProperties());
        static::assertEquals('almost-completed', $optimizationChecker->getOpenGraphLevel());
    }

    public function testTwitterPropertiesAreNotCompleted()
    {
        $optimizationChecker = $this->getOptimizationChecker('twitter-properties-missing.html');

        $propertiesExpected = [
            'card' => 'summary',
            'site' => 'Twitter Site',
            'creator' => 'Twitter Creator',
        ];

        static::assertEquals($propertiesExpected, $optimizationChecker->getTwitterProperties());
        static::assertEquals('almost-completed', $optimizationChecker->getTwitterPropertiesLevel());
    }

    public function getOptimizationChecker($filename): OptimizationChecker
    {
        $html = file_get_contents(sprintf('tests/SeoTool/OptimizationChecker/%s', $filename));
        $crawler = new Crawler($html);

        return new OptimizationChecker($crawler);
    }
}
