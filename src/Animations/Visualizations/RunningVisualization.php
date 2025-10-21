<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * RunningVisualization
 * 
 * Single colored dot racing back and forth across the strip
 * Classic WLED effect
 */
class RunningVisualization extends RGBVisualization
{
    use TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::RUNNING;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFF0000,        // Red dot
            'speed' => 50,              // Milliseconds per pixel
            'trail_length' => 0,        // No trail by default
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts) {
            // Calculate position (bounce back and forth)
            $cycleTime = $count * $opts['speed'] * 2; // Time for full back-and-forth
            $position = (int)(($elapsed % $cycleTime) / $opts['speed']);
            
            // Determine if going forward or backward
            if ($position >= $count) {
                $position = ($count * 2) - $position - 1;
            }
            
            $pixel = (int)floor($position);
            
            // Clear and draw
            $channel->clear();
            
            // Draw trail if enabled
            if ($opts['trail_length'] > 0) {
                $direction = ($elapsed % $cycleTime) < ($count * $opts['speed']) ? 1 : -1;
                
                for ($t = 1; $t <= $opts['trail_length']; $t++) {
                    $trailPixel = $pixel - ($t * $direction);
                    if ($trailPixel >= 0 && $trailPixel < $count) {
                        $brightness = 1.0 - ($t / ($opts['trail_length'] + 1));
                        $trailColor = $this->dimColor($opts['color'], $brightness);
                        $channel->setPixelColorHex($trailPixel, $trailColor);
                    }
                }
            }
            
            // Draw main dot
            if ($pixel >= 0 && $pixel < $count) {
                $channel->setPixelColorHex($pixel, $opts['color']);
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }

    /**
     * Dim a color by brightness factor
     */
    protected function dimColor(int $color, float $brightness): int
    {
        $r = (($color >> 16) & 0xFF) * $brightness;
        $g = (($color >> 8) & 0xFF) * $brightness;
        $b = ($color & 0xFF) * $brightness;
        
        return ((int)$r << 16) | ((int)$g << 8) | (int)$b;
    }
}

