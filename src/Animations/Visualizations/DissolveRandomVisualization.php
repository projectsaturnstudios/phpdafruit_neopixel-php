<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * DissolveRandomVisualization
 * 
 * Like Dissolve but transitions to completely random colors
 */
class DissolveRandomVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect;

    public function getAnimationType(): Animation
    {
        return Animation::DISSOLVE_RANDOM;
    }

    public function getDefaultOptions(): array
    {
        return [
            'transition_ms' => 2000,    // Time for full transition
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        // Initialize with random color
        $channel->fill($this->randomColor())->show();
        
        $nextTransition = $opts['transition_ms'];
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts, &$nextTransition) {
            if ($elapsed >= $nextTransition) {
                // Start new transition with random color
                $nextColor = $this->randomColor();
                
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

