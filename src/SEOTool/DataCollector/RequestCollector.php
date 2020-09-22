<?php

declare(strict_types=1);

namespace App\SEOTool\DataCollector;

use App\SEOTool\Checker\OptimizationChecker;
use App\SEOTool\Checker\RobotGuidelinesChecker;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;

class RequestCollector extends DataCollector implements LateDataCollectorInterface
{
    public $crawler;

    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $this->data = [
            'response' => $response,
        ];
    }

    public function lateCollect()
    {
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getName()
    {
        return 'app.request_collector';
    }

    public function getRobotGuidelinesChecker()
    {
        return new RobotGuidelinesChecker($this->getCrawler(), $this->data['response']);
    }

    public function getCrawler()
    {
        /** @var Response $response */
        $response = $this->data['response'];
        $crawler = new Crawler();
        $crawler->addContent($response->getContent(), 'text/html');

        return $crawler;
    }

    public function getOptimizationChecker()
    {
        return new OptimizationChecker($this->getCrawler());
    }
}
