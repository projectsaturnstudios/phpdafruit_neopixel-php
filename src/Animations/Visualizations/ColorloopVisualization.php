<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\ColorWheelEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * ColorloopVisualization
 * 
 * Cycles through the color wheel on all pixels simultaneously
 */
class ColorloopVisualization extends RGBVisualization
{
    use ColorWheelEffect, TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::COLORLOOP;
    }

    public function getDefaultOptions(): array
    {
        return [
            'speed_ms' => 50,       // Speed per frame
            'cycles' => null,       // Auto-calculate based on duration
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        // Calculate cycles if not provided
        if ($opts['cycles'] === null) {
            $opts['cycles'] = max(1, (int)($duration_ms / ($opts['speed_ms'] * 256)));
        }
        
        for ($cycle = 0; $cycle < $opts['cycles'] * 256; $cycle++) {
            $color = $this->wheel($cycle & 255);
            $channel->fill($color);
            $channel->show();
            usleep($opts['speed_ms'] * 1000);
        }
        
        $channel->clear()->show();
    }
}

