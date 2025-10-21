<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * FadeVisualization
 * 
 * Simple fade transition between colors
 */
class FadeVisualization extends RGBVisualization
{
    use FadeEffect, TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::FADE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'from_color' => 0x000000,  // Start color (black)
            'to_color' => 0xFF0000,    // End color (red)
            'curve' => 'ease_in_out',  // Fade curve
            'hold_time_ms' => 500,     // Time to hold at end before reversing
            'reverse' => true,         // Fade back to start
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        if ($opts['reverse']) {
            $halfDuration = (int)($duration_ms / 2);
            
            // Fade from -> to
            $this->runForDuration($halfDuration - $opts['hold_time_ms'], function($elapsed, $progress) use ($channel, $opts) {
                $curvedProgress = $this->fadeCurve($progress, $opts['curve']);
                $color = $this->fadeToColor($opts['from_color'], $opts['to_color'], $curvedProgress);
                $channel->fill($color);
                $channel->show();
            }, 16000);
            
            // Hold at end color
            $this->wait($opts['hold_time_ms']);
            
            // Fade back to -> from
            $this->runForDuration($halfDuration - $opts['hold_time_ms'], function($elapsed, $progress) use ($channel, $opts) {
                $curvedProgress = $this->fadeCurve($progress, $opts['curve']);
                $color = $this->fadeToColor($opts['to_color'], $opts['from_color'], $curvedProgress);
                $channel->fill($color);
                $channel->show();
            }, 16000);
        } else {
            // Single fade
            $this->runForDuration($duration_ms, function($elapsed, $progress) use ($channel, $opts) {
                $curvedProgress = $this->fadeCurve($progress, $opts['curve']);
                $color = $this->fadeToColor($opts['from_color'], $opts['to_color'], $curvedProgress);
                $channel->fill($color);
                $channel->show();
            }, 16000);
        }
        
        $channel->clear()->show();
    }
}

