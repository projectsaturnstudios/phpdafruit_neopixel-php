<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * RunningColorVisualization
 * 
 * Running dot with trailing color fade
 * Leaves a colorful trail as it moves
 */
class RunningColorVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::RUNNING_COLOR;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFF0000,        // Dot color
            'speed' => 50,              // Milliseconds per pixel
            'fade_factor' => 0.85,      // How quickly trail fades
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts) {
            // Calculate position (bounce back and forth)
            $cycleTime = $count * $opts['speed'] * 2;
            $position = (int)(($elapsed % $cycleTime) / $opts['speed']);
            
            if ($position >= $count) {
                $position = ($count * 2) - $position - 1;
            }
            
            $pixel = (int)floor($position);
            
            // Fade existing pixels
            for ($i = 0; $i < $count; $i++) {
                $current = $channel->getPixelColor($i);
                if ($current > 0) {
                    $channel->setPixelColorHex($i, $this->dimColor($current, $opts['fade_factor']));
                }
            }
            
            // Draw new dot
            if ($pixel >= 0 && $pixel < $count) {
                $channel->setPixelColorHex($pixel, $opts['color']);
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }
}

