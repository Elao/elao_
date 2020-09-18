<?php

namespace App\SEOStuff\DataCollector;

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
            'crawler' => new Crawler($response->getContent())
        ];
    }

    public function lateCollect()
    {
        /** @var Response $response */
        $response = $this->data['response'];

        $this->data['X-Robots-Tag'] = $response->headers->get('X-Robots-Tag') ;
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getTitle()
    {
        return $this->data['crawler']->extract(['h1']);
    }

    public function getName()
    {
        return 'app.request_collector';
    }

    public function getXRobotsTag()
    {
        return $this->data['X-Robots-Tag'];
    }

    public function getH1()
    {

        /** @var Response $response */
        $response = $this->data['response'];
        $html = $response->getContent();
        $crawler = new Crawler($html);
//        $p = $crawler->filter('body > p')->first();
//        return $this->data['crawler']->extract(['h1']);

    }
}
