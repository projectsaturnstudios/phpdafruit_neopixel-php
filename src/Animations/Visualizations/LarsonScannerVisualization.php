<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * LarsonScannerVisualization
 * 
 * KITT/Cylon eye scanner effect
 * Named after Glen A. Larson, creator of Knight Rider and Battlestar Galactica
 * Single bright dot with trailing fade, scanning back and forth
 */
class LarsonScannerVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::LARSON_SCANNER;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFF0000,        // Red for KITT
            'speed' => 30,              // Milliseconds per pixel
            'eye_size' => 3,            // Size of the bright center
            'fade_factor' => 0.75,      // Trail fade speed
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts) {
            // Calculate position (bounce back and forth)
            $cycleTime = $count * $opts['speed'] * 2;
            $position = (int)(($elapsed % $cycleTime) / $opts['speed']);
            
            if ($position >= $count) {
                $position = ($count * 2) - $position - 1;
            }
            
            $center = (int)floor($position);
            
            // Fade all pixels
            for ($i = 0; $i < $count; $i++) {
                $current = $channel->getPixelColor($i);
                if ($current > 0) {
                    $channel->setPixelColorHex($i, $this->dimColor($current, $opts['fade_factor']));
                }
            }
            
            // Draw eye with gradient
            for ($i = 0; $i < $opts['eye_size']; $i++) {
                $offset = $i - (int)($opts['eye_size'] / 2);
                $pixel = $center + $offset;
                
                if ($pixel >= 0 && $pixel < $count) {
                    $brightness = 1.0 - (abs($offset) / $opts['eye_size']);
                    $color = $this->dimColor($opts['color'], $brightness);
                    $channel->setPixelColorHex($pixel, $color);
                }
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }
}

