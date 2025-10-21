<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * GradientVisualization
 * 
 * Smooth color gradient that shifts and moves
 * Creates flowing color transitions
 */
class GradientVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::GRADIENT;
    }

    public function getDefaultOptions(): array
    {
        return [
            'colors' => [0xFF0000, 0x00FF00, 0x0000FF], // Colors to gradient through
            'speed' => 50,              // Movement speed
            'spread' => 3.0,            // How stretched the gradient is
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        $colorCount = count($opts['colors']);
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts, $colorCount) {
            // Calculate offset for movement
            $offset = (float)(($elapsed / $opts['speed']) % ($count * $opts['spread']));
            
            for ($i = 0; $i < $count; $i++) {
                // Calculate position in gradient space
                $position = ($i + $offset) / $opts['spread'];
                
                // Which two colors to interpolate between
                $colorIndex = (int)floor($position) % $colorCount;
                $nextColorIndex = ($colorIndex + 1) % $colorCount;
                
                // How far between the two colors (0-1)
                $t = fmod($position, 1.0);
                
                // Interpolate between colors
                $color1 = $opts['colors'][$colorIndex];
                $color2 = $opts['colors'][$nextColorIndex];
                $color = $this->lerpColor($color1, $color2, $t);
                
                $channel->setPixelColorHex($i, $color);
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }
}

