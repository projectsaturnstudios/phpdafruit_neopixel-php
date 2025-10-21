<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * RunningRandomVisualization
 * 
 * Running dot that changes to random colors
 * Colorful trail effect
 */
class RunningRandomVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect, RandomEffect;

    public function getAnimationType(): Animation
    {
        return Animation::RUNNING_RANDOM;
    }

    public function getDefaultOptions(): array
    {
        return [
            'speed' => 50,              // Milliseconds per pixel
            'fade_factor' => 0.85,      // How quickly trail fades
            'color_change_interval' => 5, // Change color every N pixels
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        $currentColor = $this->randomColor();
        $pixelsSinceChange = 0;
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts, &$currentColor, &$pixelsSinceChange) {
            // Calculate position
            $position = (int)(($elapsed / $opts['speed']) % ($count * 2));
            
            if ($position >= $count) {
                $position = ($count * 2) - $position - 1;
            }
            
            $pixel = (int)floor($position);
            
            // Change color periodically
            if ($pixelsSinceChange >= $opts['color_change_interval']) {
                $currentColor = $this->randomColor();
                $pixelsSinceChange = 0;
            }
            $pixelsSinceChange++;
            
            // Fade existing pixels
            for ($i = 0; $i < $count; $i++) {
                $current = $channel->getPixelColor($i);
                if ($current > 0) {
                    $channel->setPixelColorHex($i, $this->dimColor($current, $opts['fade_factor']));
                }
            }
            
            // Draw new dot
            if ($pixel >= 0 && $pixel < $count) {
                $channel->setPixelColorHex($pixel, $currentColor);
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }
}

