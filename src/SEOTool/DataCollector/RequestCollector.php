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
    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data = [
            'response' => $response,
        ];
    }

    public function lateCollect(): void
    {
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getName(): string
    {
        return 'app.request_collector';
    }

    public function getRobotGuidelinesChecker(): RobotGuidelinesChecker
    {
        return new RobotGuidelinesChecker($this->getCrawler(), $this->data['response']);
    }

    public function getCrawler(): Crawler
    {
        /** @var Response $response */
        $response = $this->data['response'];

        return new Crawler((string) $response->getContent(), 'text/html');
    }

    public function getOptimizationChecker(): OptimizationChecker
    {
        return new OptimizationChecker($this->getCrawler());
    }
}
