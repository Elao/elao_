<?php

declare(strict_types=1);

use App\SEOTool\DataCollector\RequestCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataCollectorTest extends TestCase
{
    public function testGetData()
    {
        $html = file_get_contents('tests/SeoTool/DataCollector/my-page.html');
        $request = new Request();
        $response = new Response($html, 200);
        $datacollector = new RequestCollector();
        $datacollector->collect($request, $response);
        $datacollector->lateCollect();

        static::assertEquals('This is H1', $datacollector->getH1());
        static::assertEquals('en', $datacollector->getLanguage());
        static::assertEquals([], $datacollector->listMissingAltFromImages());
        static::assertEquals(['icon icon--alert'], $datacollector->listNonExplicitIcons());
        static::assertEquals(['title', 'description', 'image'], $datacollector->getMissingTwitterProperties());
        static::assertEquals([], $datacollector->getMissingOpenGraphProperties());
        static::assertEquals(true, $datacollector->getOneH1());
        static::assertEquals([], $datacollector->getOpenGraphProperties());
        static::assertEquals(['card' => 'summary', 'site' => 'Twitter Site', 'creator' => 'Twitter Creator'], $datacollector->getTwitterProperties());
        static::assertEquals(2, $datacollector->getCountAllIcons());
        static::assertEquals(1, $datacollector->getCountAllExplicitIcons());
        static::assertEquals('Title', $datacollector->getTitle());
        static::assertEquals('This is meta description', $datacollector->getMetaDescription());
        static::assertEquals('missing', $datacollector->getOpenGraphLevel());
        static::assertEquals('almost-completed', $datacollector->getTwitterPropertiesLevel());
        static::assertEquals(0, $datacollector->getCountAllImages());
        static::assertEquals(0, $datacollector->getCountAltFromImages());
        static::assertEquals(null, $datacollector->getXRobotsTag());
        static::assertEquals(null, $datacollector->getCanonical());
        static::assertEquals(null, $datacollector->getMetaRobot());
        static::assertEquals(null, $datacollector->getMetaGooglebot());
        static::assertEquals(null, $datacollector->getMetaGooglebotNews());
    }
}
