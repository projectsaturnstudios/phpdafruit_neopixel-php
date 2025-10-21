<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\ColorWheelEffect;

/**
 * TheaterChaseRainbowVisualization
 * 
 * Theater marquee chase with rainbow colors
 */
class TheaterChaseRainbowVisualization extends RGBVisualization
{
    use ColorWheelEffect;

    public function getAnimationType(): Animation
    {
        return Animation::THEATER_CHASE_RAINBOW;
    }

    public function getDefaultOptions(): array
    {
        return [
            'spacing' => 3,          // Pixels between lit LEDs
            'speed_ms' => 50,        // Speed per step
            'cycles' => null,        // Auto-calculate based on duration
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        // Calculate cycles if not provided
        if ($opts['cycles'] === null) {
            $opts['cycles'] = max(1, (int)($duration_ms / ($opts['speed_ms'] * $opts['spacing'])));
        }
        
        for ($cycle = 0; $cycle < $opts['cycles']; $cycle++) {
            for ($offset = 0; $offset < $opts['spacing']; $offset++) {
                $channel->clear();
                
                // Cycle through rainbow
                $wheelPos = ($cycle * 256 / $opts['cycles']) & 255;
                $color = $this->wheel($wheelPos);
                
                for ($i = $offset; $i < $count; $i += $opts['spacing']) {
                    $channel->setPixelColorHex($i, $color);
                }
                
                $channel->show();
                usleep($opts['speed_ms'] * 1000);
            }
        }
        
        $channel->clear()->show();
    }
}

