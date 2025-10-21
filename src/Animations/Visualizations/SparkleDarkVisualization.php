<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * SparkleDarkVisualization
 * 
 * Random sparkles on a completely black background
 * Only brief flashes of light
 */
class SparkleDarkVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect;

    public function getAnimationType(): Animation
    {
        return Animation::SPARKLE_DARK;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,        // White sparkles
            'density' => 3,             // Number of sparkles per frame
            'flash_duration_ms' => 50,  // How long each sparkle lasts
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts) {
            // Clear to black
            $channel->clear();
            
            // Add random sparkles
            for ($i = 0; $i < $opts['density']; $i++) {
                $pixel = $this->randomPixel($count);
                $channel->setPixelColorHex($pixel, $opts['color']);
            }
            
            $channel->show();
            
            return $opts['flash_duration_ms'];
        });
    }
}

