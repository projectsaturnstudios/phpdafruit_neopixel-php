<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;

/**
 * DynamicVisualization
 * 
 * Smooth random color changes across all pixels
 */
class DynamicVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::DYNAMIC;
    }

    public function getDefaultOptions(): array
    {
        return [
            'transition_ms' => 2000,   // Time for each color transition
            'transitions' => null,     // Number of transitions (auto-calc if null)
            'vibrant' => true,         // Use vibrant colors
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        // Calculate number of transitions if not provided
        if ($opts['transitions'] === null) {
            $opts['transitions'] = max(2, (int)($duration_ms / $opts['transition_ms']));
        }
        
        // Start with a random color
        $currentColor = $opts['vibrant'] 
            ? $this->randomVibrantColor() 
            : $this->randomColor(64, 255);
        
        $channel->fill($currentColor);
        $channel->show();
        
        // Transition through random colors
        for ($i = 0; $i < $opts['transitions']; $i++) {
            $nextColor = $opts['vibrant'] 
                ? $this->randomVibrantColor() 
                : $this->randomColor(64, 255);
            
            // Smooth transition from current to next
            $this->runForDuration($opts['transition_ms'], function($elapsed, $progress) use ($channel, $currentColor, $nextColor) {
                $color = $this->fadeToColor($currentColor, $nextColor, $progress);
                $channel->fill($color);
                $channel->show();
            }, 16000);
            
            $currentColor = $nextColor;
        }
        
        $channel->clear()->show();
    }
}

