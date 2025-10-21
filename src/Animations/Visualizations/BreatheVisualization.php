<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * BreatheVisualization
 * 
 * Smooth breathing effect - fades in and out like breathing
 */
class BreatheVisualization extends RGBVisualization
{
    use FadeEffect, TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::BREATHE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0x0000FF,     // Blue by default
            'breath_speed_ms' => 2000, // Duration of one breath cycle
            'curve' => 'ease_in_out',  // Fade curve type
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $cycles = (int)($duration_ms / $opts['breath_speed_ms']);
        
        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            // Breathe in (fade in)
            $this->runForDuration((int)($opts['breath_speed_ms'] / 2), function($elapsed, $progress) use ($channel, $opts, $count) {
                $curvedProgress = $this->fadeCurve($progress, $opts['curve']);
                $color = $this->dimColor($opts['color'], $curvedProgress);
                $channel->fill($color);
                $channel->show();
            }, 16000);
            
            // Breathe out (fade out)
            $this->runForDuration((int)($opts['breath_speed_ms'] / 2), function($elapsed, $progress) use ($channel, $opts, $count) {
                $curvedProgress = $this->fadeCurve(1.0 - $progress, $opts['curve']);
                $color = $this->dimColor($opts['color'], $curvedProgress);
                $channel->fill($color);
                $channel->show();
            }, 16000);
        }
        
        $channel->clear()->show();
    }
}

