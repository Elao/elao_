<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle;

use App\Bridge\Glide\Bundle\Manipulator\PositionableFillSize;
use League\Glide\Manipulators\ManipulatorInterface;
use League\Glide\Manipulators\Size;
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

    /**
     * Swap Glide's size manipulator for ours, adding support for the `fillpos` param.
     *
     * @return ManipulatorInterface[]
     */
    public function getManipulators()
    {
        return array_map(
            fn (ManipulatorInterface $manipulator): ManipulatorInterface => $manipulator instanceof Size
                ? new PositionableFillSize($this->getMaxImageSize())
                : $manipulator,
            parent::getManipulators(),
        );
    }

    public static function createWithSkippedTypes(SkippedTypes $skippedTypes, array $config = []): Server
    {
        $server = parent::create($config);
        $decoratedFactory = (new self($skippedTypes, $config));

        $server->setApi($decoratedFactory->getSkippingApi());

        return $server;
    }
}
