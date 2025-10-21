<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * CometVisualization
 * 
 * Comet with a long trailing tail
 * One-directional movement with exponential fade
 */
class CometVisualization extends RGBVisualization
{
    use TimingEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::COMET;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,        // White comet
            'speed' => 30,              // Milliseconds per pixel
            'tail_length' => 10,        // Length of the tail
            'reverse' => false,         // Direction
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $this->runForDuration($duration_ms, function($elapsed) use ($channel, $count, $opts) {
            $position = (int)(($elapsed / $opts['speed']) % $count);
            
            if ($opts['reverse']) {
                $position = $count - 1 - $position;
            }
            
            // Clear strip
            $channel->clear();
            
            // Draw comet head
            $channel->setPixelColorHex($position, $opts['color']);
            
            // Draw exponential tail
            for ($i = 1; $i <= $opts['tail_length']; $i++) {
                $tailPixel = $opts['reverse'] ? $position + $i : $position - $i;
                
                if ($tailPixel >= 0 && $tailPixel < $count) {
                    // Exponential brightness falloff
                    $brightness = pow(1.0 - ($i / $opts['tail_length']), 2);
                    $color = $this->dimColor($opts['color'], $brightness);
                    $channel->setPixelColorHex($tailPixel, $color);
                }
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }
}

