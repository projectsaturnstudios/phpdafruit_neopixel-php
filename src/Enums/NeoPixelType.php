<?php

namespace PhpdaFruit\NeoPixels\Enums;

enum NeoPixelType: string
{
    case RGB = 'RGB';
    case GRB = 'GRB';
    case RGBW = 'RGBW';
    case GRBW = 'GRBW';
}