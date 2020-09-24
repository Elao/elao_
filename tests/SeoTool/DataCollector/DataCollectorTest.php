<?php

declare(strict_types=1);

use App\SEOTool\Checker\ImageChecker;
use App\SEOTool\Checker\OptimizationChecker;
use App\SEOTool\Checker\RobotGuidelinesChecker;
use App\SEOTool\DataCollector\RequestCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class DataCollectorTest extends TestCase
{
    public function testGetCrawlerAndCheckers()
    {
        $response = new Response();
        $html = file_get_contents(sprintf('tests/SeoTool/DataCollector/my-page.html'));

        $response->setContent($html);

        $requestCollector = new RequestCollector();
        $reflectionClass = new ReflectionClass($requestCollector);
        $property = $reflectionClass->getProperty('data');
        $property->setAccessible(true);
        $property->setValue($requestCollector, ['response' => $response]);

        static::assertInstanceOf(Crawler::class, $requestCollector->getCrawler());

        $robot = $requestCollector->getCheckers()['robotGuidelines'];
        $image = $requestCollector->getCheckers()['image'];
        $optimization = $requestCollector->getCheckers()['optimization'];

        static::assertInstanceOf(RobotGuidelinesChecker::class, $robot);
        static::assertInstanceOf(ImageChecker::class, $image);
        static::assertInstanceOf(OptimizationChecker::class, $optimization);
    }
}
