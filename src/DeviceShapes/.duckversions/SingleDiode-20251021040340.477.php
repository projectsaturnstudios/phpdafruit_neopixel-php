<?php

namespace PhpdaFruit\NeoPixels\DeviceShapes;

use Phpdafruit\NeoPixels\PixelChannel;

class SingleDiode extends PixelChannel
{
    public function __construct(
        protected string $path = '/dev/spidev0.0',
        NeoPixelType $type = NeoPixelType::RGB
    ) {
        parent::__construct(1, $path, $type);
    }
}