<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * StrobeVisualization
 * 
 * Fast strobe light effect with optional fade
 */
class StrobeVisualization extends RGBVisualization
{
    use FadeEffect, TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::STROBE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,      // White strobe
            'strobe_speed_ms' => 100, // Time between strobes
            'fade_duration_ms' => 30, // Duration of fade (0 = instant)
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $strobes = (int)($duration_ms / $opts['strobe_speed_ms']);
        
        for ($i = 0; $i < $strobes; $i++) {
            if ($opts['fade_duration_ms'] > 0) {
                // Flash on with fade
                $this->runForDuration($opts['fade_duration_ms'], function($elapsed, $progress) use ($channel, $opts) {
                    $color = $this->dimColor($opts['color'], $progress);
                    $channel->fill($color);
                    $channel->show();
                }, 8000);
                
                // Flash off with fade
                $this->runForDuration($opts['fade_duration_ms'], function($elapsed, $progress) use ($channel, $opts) {
                    $color = $this->dimColor($opts['color'], 1.0 - $progress);
                    $channel->fill($color);
                    $channel->show();
                }, 8000);
                
                // Wait remainder
                $waitTime = $opts['strobe_speed_ms'] - ($opts['fade_duration_ms'] * 2);
                if ($waitTime > 0) {
                    $this->wait($waitTime);
                }
            } else {
                // Instant on/off
                $channel->fill($opts['color']);
                $channel->show();
                $this->wait((int)($opts['strobe_speed_ms'] / 4));
                
                $channel->clear();
                $channel->show();
                $this->wait((int)($opts['strobe_speed_ms'] * 3 / 4));
            }
        }
        
        $channel->clear()->show();
    }
}

