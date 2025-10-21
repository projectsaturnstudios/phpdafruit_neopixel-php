<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * StaticVisualization
 * 
 * Simple solid color display
 */
class StaticVisualization extends RGBVisualization
{
    use TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::STATIC;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,  // White by default
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        // Set solid color
        $channel->fill($opts['color']);
        $channel->show();
        
        // Hold for duration
        $this->wait($duration_ms);
        
        // Optional: clear after
        $channel->clear()->show();
    }
}

