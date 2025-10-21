<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;

/**
 * WipeRandomVisualization
 * 
 * Color wipe with random colors
 */
class WipeRandomVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect;

    public function getAnimationType(): Animation
    {
        return Animation::WIPE_RANDOM;
    }

    public function getDefaultOptions(): array
    {
        return [
            'wipes' => 3,              // Number of wipes
            'reverse' => false,        // Wipe direction
            'vibrant' => true,         // Use vibrant colors
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $wipeTime = (int)($duration_ms / $opts['wipes']);
        
        for ($w = 0; $w < $opts['wipes']; $w++) {
            // Generate random color for this wipe
            $color = $opts['vibrant'] 
                ? $this->randomVibrantColor() 
                : $this->randomColor(64, 255);
            
            // Wipe on
            $this->runForFrames($count, function($frame, $progress) use ($channel, $opts, $count, $color) {
                $pixel = $opts['reverse'] ? ($count - 1 - $frame) : $frame;
                $channel->setPixelColorHex($pixel, $color);
                $channel->show();
            }, $this->calculateFrameDelay((int)($count * 1000 / $wipeTime)));
        }
        
        $channel->clear()->show();
    }
}

