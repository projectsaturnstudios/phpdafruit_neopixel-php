<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;

/**
 * RandomColorsVisualization
 * 
 * Random colors on each LED, changing over time
 */
class RandomColorsVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect;

    public function getAnimationType(): Animation
    {
        return Animation::RANDOM_COLORS;
    }

    public function getDefaultOptions(): array
    {
        return [
            'change_speed_ms' => 500,  // Time between color changes
            'vibrant' => false,        // Use vibrant colors
            'change_all' => true,      // Change all pixels at once or one at a time
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $changes = (int)($duration_ms / $opts['change_speed_ms']);
        
        for ($i = 0; $i < $changes; $i++) {
            if ($opts['change_all']) {
                // Change all pixels to random colors
                for ($p = 0; $p < $count; $p++) {
                    $color = $opts['vibrant'] 
                        ? $this->randomVibrantColor() 
                        : $this->randomColor(64, 255);
                    $channel->setPixelColorHex($p, $color);
                }
            } else {
                // Change one random pixel
                $pixel = $this->randomPixel(0, $count - 1);
                $color = $opts['vibrant'] 
                    ? $this->randomVibrantColor() 
                    : $this->randomColor(64, 255);
                $channel->setPixelColorHex($pixel, $color);
            }
            
            $channel->show();
            $this->wait($opts['change_speed_ms']);
        }
        
        $channel->clear()->show();
    }
}

