<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;
use PhpdaFruit\NeoPixels\Animations\Effects\ColorWheelEffect;

/**
 * RainbowVisualization
 * 
 * Animated rainbow cycle effect
 */
class RainbowVisualization extends RGBVisualization
{
    use ColorWheelEffect;
    public function getAnimationType(): Animation
    {
        return Animation::RAINBOW;
    }

    public function getDefaultOptions(): array
    {
        return [
            'speed_ms' => 50,    // Speed per frame
            'cycles' => null,    // Auto-calculate based on duration
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        // Calculate cycles if not provided
        if ($opts['cycles'] === null) {
            $opts['cycles'] = max(1, (int)($duration_ms / ($opts['speed_ms'] * 256)));
        }
        
        // Use existing method if channel is RGBStrip
        if ($channel instanceof RGBStrip) {
            $channel->rainbowCycle($opts['cycles'], $opts['speed_ms']);
            return;
        }
        
        // Otherwise implement manually
        $count = $channel->getPixelCount();
        
        for ($cycle = 0; $cycle < $opts['cycles'] * 256; $cycle++) {
            for ($i = 0; $i < $count; $i++) {
                $color = $this->wheel((($i * 256 / $count) + $cycle) & 255);
                $channel->setPixelColorHex($i, $color);
            }
            $channel->show();
            usleep($opts['speed_ms'] * 1000);
        }
    }
}

