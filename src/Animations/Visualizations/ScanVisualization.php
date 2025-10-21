<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;

/**
 * ScanVisualization
 * 
 * KITT/Larson Scanner effect - single LED bouncing back and forth
 */
class ScanVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::SCAN;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFF0000,      // Red scanner
            'tail_length' => 3,       // Length of fade tail
            'speed_ms' => 30,         // Speed per pixel
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $timer = $this->createTimer($duration_ms);
        
        while (!$timer->isComplete()) {
            // Scan right
            for ($pos = 0; $pos < $count && !$timer->isComplete(); $pos++) {
                $channel->clear();
                
                // Draw main pixel and tail
                for ($t = 0; $t < $opts['tail_length']; $t++) {
                    $pixel = $pos - $t;
                    if ($pixel >= 0 && $pixel < $count) {
                        $brightness = 1.0 - ($t / $opts['tail_length']);
                        $color = $this->dimColor($opts['color'], $brightness);
                        $channel->setPixelColorHex($pixel, $color);
                    }
                }
                
                $channel->show();
                $this->wait($opts['speed_ms']);
            }
            
            // Scan left
            for ($pos = $count - 1; $pos >= 0 && !$timer->isComplete(); $pos--) {
                $channel->clear();
                
                // Draw main pixel and tail
                for ($t = 0; $t < $opts['tail_length']; $t++) {
                    $pixel = $pos + $t;
                    if ($pixel >= 0 && $pixel < $count) {
                        $brightness = 1.0 - ($t / $opts['tail_length']);
                        $color = $this->dimColor($opts['color'], $brightness);
                        $channel->setPixelColorHex($pixel, $color);
                    }
                }
                
                $channel->show();
                $this->wait($opts['speed_ms']);
            }
        }
        
        $channel->clear()->show();
    }
}

