<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * SawVisualization
 * 
 * Sawtooth brightness wave - brightness ramps up linearly then drops
 * Creates a "sawtooth" pattern over time
 */
class SawVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::SAW;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFF0000,        // Base color
            'cycle_ms' => 1000,         // Time for one sawtooth cycle
            'reverse' => false,         // If true, brightness ramps down
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $opts) {
            // Calculate brightness based on sawtooth wave
            $cyclePosition = ($elapsed % $opts['cycle_ms']) / $opts['cycle_ms'];
            
            $brightness = $opts['reverse'] ? (1.0 - $cyclePosition) : $cyclePosition;
            
            $color = $this->dimColor($opts['color'], $brightness);
            
            $channel->fill($color)->show();
            
            return 20; // 50fps
        });
    }
}

