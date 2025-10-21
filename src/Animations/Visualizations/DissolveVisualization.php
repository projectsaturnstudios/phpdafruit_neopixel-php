<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * DissolveVisualization
 * 
 * Transitions between colors by randomly dissolving pixels
 * Creates organic color transitions
 */
class DissolveVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect;

    public function getAnimationType(): Animation
    {
        return Animation::DISSOLVE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'colors' => [0xFF0000, 0x00FF00, 0x0000FF], // Colors to cycle through
            'transition_ms' => 2000,    // Time for full transition
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        $colorCount = count($opts['colors']);
        
        // Initialize with first color
        $channel->fill($opts['colors'][0])->show();
        
        $colorIndex = 0;
        $nextTransition = $opts['transition_ms'];
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts, &$colorIndex, &$nextTransition, $colorCount) {
            if ($elapsed >= $nextTransition) {
                // Start new transition
                $currentColor = $opts['colors'][$colorIndex % $colorCount];
                $colorIndex++;
                $nextColor = $opts['colors'][$colorIndex % $colorCount];
                
                // Create random pixel order for dissolve
                $pixels = range(0, $count - 1);
                shuffle($pixels);
                
                // Dissolve transition
                $stepsPerPixel = (int)($opts['transition_ms'] / $count);
                
                foreach ($pixels as $pixel) {
                    $channel->setPixelColorHex($pixel, $nextColor);
                    $channel->show();
                    usleep($stepsPerPixel * 1000);
                }
                
                $nextTransition = $elapsed + $opts['transition_ms'];
            }
            
            return 100; // Check every 100ms
        });
    }
}

