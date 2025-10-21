<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;

/**
 * ScanDualVisualization
 * 
 * Dual KITT/Larson Scanner effect - two scanners bouncing in opposite directions
 */
class ScanDualVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::SCAN_DUAL;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color1' => 0xFF0000,     // Red scanner
            'color2' => 0x0000FF,     // Blue scanner
            'tail_length' => 3,       // Length of fade tail
            'speed_ms' => 30,         // Speed per pixel
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $timer = $this->createTimer($duration_ms);
        $pos1 = 0;
        $pos2 = $count - 1;
        $dir1 = 1;  // Moving right
        $dir2 = -1; // Moving left
        
        while (!$timer->isComplete()) {
            $channel->clear();
            
            // Draw first scanner with tail
            for ($t = 0; $t < $opts['tail_length']; $t++) {
                $pixel = $pos1 - ($t * $dir1);
                if ($pixel >= 0 && $pixel < $count) {
                    $brightness = 1.0 - ($t / $opts['tail_length']);
                    $color = $this->dimColor($opts['color1'], $brightness);
                    $channel->setPixelColorHex($pixel, $color);
                }
            }
            
            // Draw second scanner with tail
            for ($t = 0; $t < $opts['tail_length']; $t++) {
                $pixel = $pos2 - ($t * $dir2);
                if ($pixel >= 0 && $pixel < $count) {
                    $brightness = 1.0 - ($t / $opts['tail_length']);
                    $color = $this->dimColor($opts['color2'], $brightness);
                    
                    // Blend if overlapping with first scanner
                    $existingColor = $channel->getPixelColor($pixel);
                    if ($existingColor > 0) {
                        // Simple additive blending
                        $r1 = ($existingColor >> 16) & 0xFF;
                        $g1 = ($existingColor >> 8) & 0xFF;
                        $b1 = $existingColor & 0xFF;
                        
                        $r2 = ($color >> 16) & 0xFF;
                        $g2 = ($color >> 8) & 0xFF;
                        $b2 = $color & 0xFF;
                        
                        $r = min(255, $r1 + $r2);
                        $g = min(255, $g1 + $g2);
                        $b = min(255, $b1 + $b2);
                        
                        $color = ($r << 16) | ($g << 8) | $b;
                    }
                    
                    $channel->setPixelColorHex($pixel, $color);
                }
            }
            
            $channel->show();
            $this->wait($opts['speed_ms']);
            
            // Update positions
            $pos1 += $dir1;
            $pos2 += $dir2;
            
            // Bounce first scanner
            if ($pos1 >= $count - 1) {
                $pos1 = $count - 1;
                $dir1 = -1;
            } elseif ($pos1 <= 0) {
                $pos1 = 0;
                $dir1 = 1;
            }
            
            // Bounce second scanner
            if ($pos2 >= $count - 1) {
                $pos2 = $count - 1;
                $dir2 = -1;
            } elseif ($pos2 <= 0) {
                $pos2 = 0;
                $dir2 = 1;
            }
        }
        
        $channel->clear()->show();
    }
}

