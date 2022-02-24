<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle;

use League\Glide\ServerFactory;

class DecoratingApiServerFactory extends ServerFactory
{
    public function getApi()
    {
        // @phpstan-ignore-next-line
        return new SkippingMimeTypesApi(parent::getApi(), $this->config['skipped_types'] ?? []);
    }

    public static function create(array $config = [])
    {
        $server = parent::create($config);
        $decoratedFactory = (new self($config));

        $server->setApi($decoratedFactory->getApi());

        return $server;
    }
}
