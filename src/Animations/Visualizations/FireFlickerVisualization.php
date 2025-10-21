<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * FireFlickerVisualization
 * 
 * Realistic fire simulation with flickering
 * Uses orange/red/yellow colors with random brightness variations
 */
class FireFlickerVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect;

    public function getAnimationType(): Animation
    {
        return Animation::FIRE_FLICKER;
    }

    public function getDefaultOptions(): array
    {
        return [
            'intensity' => 0.8,         // Base brightness (0-1)
            'speed' => 30,              // Update speed
            'cooling' => 55,            // How much cooler the flame gets at the top
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        // Heat map for each pixel
        $heat = array_fill(0, $count, 0);
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts, &$heat) {
            // Cool down every pixel a little
            for ($i = 0; $i < $count; $i++) {
                $cooldown = rand(0, (int)((($opts['cooling'] * 10) / $count) + 2));
                $heat[$i] = max(0, $heat[$i] - $cooldown);
            }
            
            // Heat from each pixel drifts up and diffuses
            for ($i = $count - 1; $i >= 2; $i--) {
                $heat[$i] = ($heat[$i - 1] + $heat[$i - 2] + $heat[$i - 2]) / 3;
            }
            
            // Randomly ignite new sparks near the bottom
            if (rand(0, 255) < 160) {
                $y = rand(0, min(7, $count - 1));
                $heat[$y] = min(255, $heat[$y] + rand(160, 255));
            }
            
            // Convert heat to LED colors
            for ($i = 0; $i < $count; $i++) {
                $color = $this->heatColor($heat[$i], $opts['intensity']);
                $channel->setPixelColorHex($i, $color);
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }

    /**
     * Convert heat value to fire color
     * 
     * @param int $heat Heat value (0-255)
     * @param float $intensity Overall brightness
     * @return int RGB color
     */
    protected function heatColor(int $heat, float $intensity): int
    {
        $heat = max(0, min(255, $heat));
        
        // Scale heat
        $t192 = (int)round(($heat / 255.0) * 191);
        
        // Calculate cool->hot color
        $heatramp = $t192 & 0x3F; // 0..63
        $heatramp <<= 2; // Scale up to 0..252
        
        if ($t192 & 0x80) {
            // Hottest: yellow to white
            $r = 255;
            $g = 255;
            $b = $heatramp;
        } elseif ($t192 & 0x40) {
            // Hot: red to yellow
            $r = 255;
            $g = $heatramp;
            $b = 0;
        } else {
            // Cool: black to red
            $r = $heatramp;
            $g = 0;
            $b = 0;
        }
        
        // Apply intensity
        $r = (int)($r * $intensity);
        $g = (int)($g * $intensity);
        $b = (int)($b * $intensity);
        
        return ($r << 16) | ($g << 8) | $b;
    }
}

