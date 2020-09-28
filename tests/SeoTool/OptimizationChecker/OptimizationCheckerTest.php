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

        static::assertEquals($optimizationChecker->getOpenGraphLevel(), 'missing');
    }

    public function testNoTwitterProperties()
    {
        $optimizationChecker = $this->getOptimizationChecker('no-twitter-properties.html');

        static::assertEquals($optimizationChecker->getTwitterPropertiesLevel(), 'missing');
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

        static::assertEquals($optimizationChecker->getTitle(), 'This is Title');
        static::assertEquals($optimizationChecker->getMetaDescription(), 'This is meta description');
        static::assertEquals($optimizationChecker->getH1(), 'This is H1');
        static::assertEquals($optimizationChecker->getTwitterPropertiesLevel(), 'completed');
        static::assertEquals($optimizationChecker->getOpenGraphLevel(), 'completed');
        static::assertIsArray($optimizationChecker->getTwitterProperties());
        static::assertEquals($optimizationChecker->getTwitterProperties(), $twitterExpected);
        static::assertEquals($optimizationChecker->getOpenGraphProperties(), $openGraphExpected);
    }

    public function testOgIsNotComplete()
    {
        $optimizationChecker = $this->getOptimizationChecker('og-properties-missing.html');

        $openGraphExpected = [
            'url' => 'http://localhost:8080/',
            'site_name' => 'name',
        ];

        static::assertEquals($optimizationChecker->getOpenGraphProperties(), $openGraphExpected);
        static::assertEquals($optimizationChecker->getOpenGraphLevel(), 'almost-completed');
    }

    public function testTwitterPropertiesAreNotCompleted()
    {
        $optimizationChecker = $this->getOptimizationChecker('twitter-properties-missing.html');

        $propertiesExpected = [
            'card' => 'summary',
            'site' => 'Twitter Site',
            'creator' => 'Twitter Creator',
        ];

        static::assertEquals($optimizationChecker->getTwitterProperties(), $propertiesExpected);
        static::assertEquals($optimizationChecker->getTwitterPropertiesLevel(), 'almost-completed');
    }

    public function getOptimizationChecker($filename): OptimizationChecker
    {
        $html = file_get_contents(sprintf('tests/SeoTool/OptimizationChecker/%s', $filename));
        $crawler = new Crawler($html);

        return new OptimizationChecker($crawler);
    }
}
