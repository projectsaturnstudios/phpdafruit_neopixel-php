<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * RunningRedBlueVisualization
 * 
 * Red and blue dots running in opposite directions
 * Classic two-color chase effect
 */
class RunningRedBlueVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::RUNNING_RED_BLUE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'speed' => 50,              // Milliseconds per pixel
            'fade_factor' => 0.85,      // How quickly trail fades
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts) {
            // Calculate positions for both dots
            $position = (int)(($elapsed / $opts['speed']) % $count);
            
            $redPixel = (int)floor($position);
            $bluePixel = $count - 1 - $redPixel;
            
            // Fade existing pixels
            for ($i = 0; $i < $count; $i++) {
                $current = $channel->getPixelColor($i);
                if ($current > 0) {
                    $channel->setPixelColorHex($i, $this->dimColor($current, $opts['fade_factor']));
                }
            }
            
            // Draw red dot moving forward
            if ($redPixel >= 0 && $redPixel < $count) {
                $channel->setPixelColorHex($redPixel, 0xFF0000);
            }
            
            // Draw blue dot moving backward
            if ($bluePixel >= 0 && $bluePixel < $count && $bluePixel != $redPixel) {
                $channel->setPixelColorHex($bluePixel, 0x0000FF);
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }
}

