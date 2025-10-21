<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\RandomEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * RainVisualization
 * 
 * Rain drops falling down the strip
 * Random drops that fall and fade
 */
class RainVisualization extends RGBVisualization
{
    use TimingEffect, RandomEffect, FadeEffect;

    public function getAnimationType(): Animation
    {
        return Animation::RAIN;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0x0000FF,        // Blue rain
            'intensity' => 0.1,         // Probability of new drop
            'speed' => 50,              // Fall speed (ms per pixel)
            'fade_factor' => 0.7,       // Trail fade
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        // Track active rain drops [position => [color, speed]]
        $drops = [];
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts, &$drops) {
            // Fade existing pixels
            for ($i = 0; $i < $count; $i++) {
                $current = $channel->getPixelColor($i);
                if ($current > 0) {
                    $channel->setPixelColorHex($i, $this->dimColor($current, $opts['fade_factor']));
                }
            }
            
            // Create new drops at top
            if ($this->randomFloat() < $opts['intensity']) {
                $drops[] = [
                    'position' => 0,
                    'color' => $opts['color'],
                    'speed' => $opts['speed'] + rand(-10, 10) // Slight variation
                ];
            }
            
            // Move drops down
            foreach ($drops as $key => $drop) {
                $pixel = (int)floor($drop['position']);
                
                if ($pixel >= 0 && $pixel < $count) {
                    $channel->setPixelColorHex($pixel, $drop['color']);
                }
                
                // Move drop down
                $drops[$key]['position'] += 1;
                
                // Remove if off screen
                if ($drops[$key]['position'] >= $count) {
                    unset($drops[$key]);
                }
            }
            
            $channel->show();
            
            return $opts['speed'];
        });
    }
}

