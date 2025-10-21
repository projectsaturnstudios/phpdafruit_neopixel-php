<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * WipeVisualization
 * 
 * Color wipe across the strip
 */
class WipeVisualization extends RGBVisualization
{
    use TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::WIPE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFF0000,      // Red wipe
            'reverse' => false,       // Wipe direction
            'clear_after' => true,    // Clear after wipe
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $halfDuration = $opts['clear_after'] ? (int)($duration_ms / 2) : $duration_ms;
        
        // Wipe on
        $this->runForFrames($count, function($frame, $progress) use ($channel, $opts, $count) {
            $pixel = $opts['reverse'] ? ($count - 1 - $frame) : $frame;
            $channel->setPixelColorHex($pixel, $opts['color']);
            $channel->show();
        }, $this->calculateFrameDelay((int)($count * 1000 / $halfDuration)));
        
        // Wipe off if requested
        if ($opts['clear_after']) {
            $this->runForFrames($count, function($frame, $progress) use ($channel, $opts, $count) {
                $pixel = $opts['reverse'] ? $frame : ($count - 1 - $frame);
                $channel->setPixelColorHex($pixel, 0x000000);
                $channel->show();
            }, $this->calculateFrameDelay((int)($count * 1000 / $halfDuration)));
        }
    }
}

