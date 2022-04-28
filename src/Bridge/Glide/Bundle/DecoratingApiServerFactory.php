<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle;

use League\Glide\Server;
use League\Glide\ServerFactory;

class DecoratingApiServerFactory extends ServerFactory
{
    public function __construct(private SkippedTypes $skippedTypes, array $config = [])
    {
        parent::__construct($config);
    }

    public function getSkippingApi(): SkippingMimeTypesApi
    {
        return new SkippingMimeTypesApi(parent::getApi(), $this->skippedTypes);
    }

    public static function createWithSkippedTypes(SkippedTypes $skippedTypes, array $config = []): Server
    {
        $server = parent::create($config);
        $decoratedFactory = (new self($skippedTypes, $config));

        $server->setApi($decoratedFactory->getSkippingApi());

        return $server;
    }
}
