<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * SparklePlusVisualization
 * 
 * Sparkle effect with color bleeding to adjacent pixels
 * Creates a more intense sparkle effect
 */
class SparklePlusVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::SPARKLE_PLUS;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,        // Base color
            'density' => 2,             // Number of sparkles per frame
            'bleed_distance' => 2,      // How many adjacent pixels light up
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        // Fill with base color
        $channel->fill($opts['color'])->show();
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts) {
            // Reset to base color
            $channel->fill($opts['color']);
            
            // Add sparkles with bleeding
            for ($i = 0; $i < $opts['density']; $i++) {
                $center = $this->randomPixel($count);
                
                // Bright center
                $channel->setPixelColorHex($center, 0xFFFFFF);
                
                // Dimmer adjacent pixels (bleed effect)
                for ($d = 1; $d <= $opts['bleed_distance']; $d++) {
                    $brightness = 1.0 - ($d / ($opts['bleed_distance'] + 1));
                    $bleedColor = $this->lerpColor($opts['color'], 0xFFFFFF, $brightness);
                    
                    if ($center - $d >= 0) {
                        $channel->setPixelColorHex($center - $d, $bleedColor);
                    }
                    if ($center + $d < $count) {
                        $channel->setPixelColorHex($center + $d, $bleedColor);
                    }
                }
            }
            
            $channel->show();
            
            return 50; // 20fps
        });
    }
}

