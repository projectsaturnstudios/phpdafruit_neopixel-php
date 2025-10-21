<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * FireworksVisualization
 * 
 * Fireworks explosion effect
 * Random bursts that expand and fade
 */
class FireworksVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::FIREWORKS;
    }

    public function getDefaultOptions(): array
    {
        return [
            'frequency' => 0.05,        // Probability of new firework per frame
            'fade_speed' => 0.85,       // How fast explosions fade
            'explosion_size' => 5,      // How many pixels light up
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts) {
            // Fade existing pixels
            for ($i = 0; $i < $count; $i++) {
                $current = $channel->getPixelColor($i);
                if ($current > 0) {
                    $channel->setPixelColorHex($i, $this->dimColor($current, $opts['fade_speed']));
                }
            }
            
            // Random new firework
            if ($this->randomFloat() < $opts['frequency']) {
                $center = $this->randomPixel($count);
                $color = $this->randomVibrantColor();
                
                // Explosion from center point
                for ($i = 0; $i < $opts['explosion_size']; $i++) {
                    $offset = $i - (int)($opts['explosion_size'] / 2);
                    $pixel = $center + $offset;
                    
                    if ($pixel >= 0 && $pixel < $count) {
                        // Brighter in center, dimmer at edges
                        $brightness = 1.0 - (abs($offset) / $opts['explosion_size']);
                        $pixelColor = $this->dimColor($color, $brightness);
                        $channel->setPixelColorHex($pixel, $pixelColor);
                    }
                }
            }
            
            $channel->show();
            
            return 50; // 20fps
        });
    }
}

