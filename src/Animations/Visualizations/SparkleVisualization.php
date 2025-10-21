<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;

/**
 * SparkleVisualization
 * 
 * Random sparkle effect with dimming
 */
class SparkleVisualization extends RGBVisualization
{
    use FadeEffect;
    public function getAnimationType(): Animation
    {
        return Animation::SPARKLE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,      // White sparkles
            'density' => 3,           // Number of sparkles per frame
            'dim_factor' => 0.7,      // How much to dim each frame
            'frame_delay_ms' => 50,   // Delay between frames
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        // Use existing method if channel is RGBStrip
        if ($channel instanceof RGBStrip) {
            $channel->sparkle($opts['color'], $duration_ms, $opts['density']);
            return;
        }
        
        // Otherwise implement manually
        $iterations = (int)($duration_ms / $opts['frame_delay_ms']);
        
        for ($i = 0; $i < $iterations; $i++) {
            // Add new sparkles
            for ($d = 0; $d < $opts['density']; $d++) {
                $pixel = rand(0, $count - 1);
                $channel->setPixelColorHex($pixel, $opts['color']);
            }
            
            $channel->show();
            usleep($opts['frame_delay_ms'] * 1000);
            
            // Dim all pixels
            for ($p = 0; $p < $count; $p++) {
                $currentColor = $channel->getPixelColor($p);
                if ($currentColor > 0) {
                    $dimmed = $this->dimColor($currentColor, $opts['dim_factor']);
                    $channel->setPixelColorHex($p, $dimmed);
                }
            }
        }
        
        $channel->clear()->show();
    }
}

