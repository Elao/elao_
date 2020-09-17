<?php

namespace App\SEOStuff\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class RequestCollector extends DataCollector
{
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $this->data = [
            'method' => $response,
            'acceptable_content_types' => $request->getAcceptableContentTypes(),
            'X-Robots-Tag' => $response->headers->count()
        ];
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getName()
    {
        return 'app.request_collector';
    }
    public function getMethod()
    {
        return $this->data['method'];
    }

    public function getAcceptableContentTypes()
    {
        return $this->data['acceptable_content_types'];
    }
    public function getXRobotsTag()
    {
        dump($this->data['X-Robots-Tag']);
        die;
        return $this->data['X-Robots-Tag'];
    }
}
