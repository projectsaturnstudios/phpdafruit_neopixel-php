<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * TwinkleVisualization
 * 
 * Random pixels twinkle like stars in the night sky
 * Pixels fade in and out randomly
 */
class TwinkleVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::TWINKLE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,        // White stars
            'density' => 0.1,           // Probability a pixel twinkles (0-1)
            'fade_speed' => 0.9,        // How fast pixels fade out
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        // Track brightness of each pixel
        $brightness = array_fill(0, $count, 0.0);
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts, &$brightness) {
            // Randomly activate new twinkles
            for ($i = 0; $i < $count; $i++) {
                if ($brightness[$i] == 0 && $this->randomFloat() < $opts['density']) {
                    $brightness[$i] = 1.0;
                }
            }
            
            // Update all pixels
            for ($i = 0; $i < $count; $i++) {
                if ($brightness[$i] > 0) {
                    $color = $this->dimColor($opts['color'], $brightness[$i]);
                    $channel->setPixelColorHex($i, $color);
                    
                    // Fade out
                    $brightness[$i] *= $opts['fade_speed'];
                    if ($brightness[$i] < 0.01) {
                        $brightness[$i] = 0;
                    }
                } else {
                    $channel->setPixelColorHex($i, 0x000000);
                }
            }
            
            $channel->show();
            
            return 50; // 20fps
        });
    }
}

