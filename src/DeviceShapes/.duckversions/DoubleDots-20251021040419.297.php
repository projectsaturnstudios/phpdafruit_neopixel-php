<?php

namespace PhpdaFruit\NeoPixels\DeviceShapes;

use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use Phpdafruit\NeoPixels\PixelChannel;

class DoubleDots extends PixelChannel
{
    public function __construct(
        protected string $path = '/dev/spidev0.0',
        NeoPixelType $type = NeoPixelType::RGB
    ) {
        parent::__construct(2, $path, $type);
    }
}