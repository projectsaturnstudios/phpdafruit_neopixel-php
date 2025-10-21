<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\ColorWheelEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * BlinkRainbowVisualization
 * 
 * Blinks through rainbow colors
 */
class BlinkRainbowVisualization extends RGBVisualization
{
    use ColorWheelEffect, TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::BLINK_RAINBOW;
    }

    public function getDefaultOptions(): array
    {
        return [
            'blink_speed_ms' => 200,  // Speed per blink
            'colors_per_cycle' => 8,  // Number of rainbow colors per cycle
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        $blinks = (int)($duration_ms / $opts['blink_speed_ms']);
        $colorStep = (int)(256 / $opts['colors_per_cycle']);
        
        for ($i = 0; $i < $blinks; $i++) {
            $wheelPos = ($i * $colorStep) & 255;
            $color = $this->wheel($wheelPos);
            
            // On
            $channel->fill($color);
            $channel->show();
            $this->wait((int)($opts['blink_speed_ms'] / 2));
            
            // Off
            $channel->clear();
            $channel->show();
            $this->wait((int)($opts['blink_speed_ms'] / 2));
        }
        
        $channel->clear()->show();
    }
}

