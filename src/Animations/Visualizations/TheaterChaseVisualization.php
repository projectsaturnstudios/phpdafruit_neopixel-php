<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;

/**
 * TheaterChaseVisualization
 * 
 * Theater marquee chase effect
 */
class TheaterChaseVisualization extends RGBVisualization
{
    public function getAnimationType(): Animation
    {
        return Animation::THEATER_CHASE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,     // White by default
            'spacing' => 3,          // Pixels between lit LEDs
            'speed_ms' => 50,        // Speed per step
            'cycles' => null,        // Auto-calculate based on duration
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        // Calculate cycles if not provided
        if ($opts['cycles'] === null) {
            $opts['cycles'] = max(1, (int)($duration_ms / ($opts['speed_ms'] * $opts['spacing'])));
        }
        
        // Use existing method if channel is RGBStrip
        if ($channel instanceof RGBStrip) {
            $channel->theaterChase($opts['color'], $opts['spacing'], $opts['cycles'], $opts['speed_ms']);
            return;
        }
        
        // Otherwise implement manually
        $count = $channel->getPixelCount();
        
        for ($cycle = 0; $cycle < $opts['cycles']; $cycle++) {
            for ($offset = 0; $offset < $opts['spacing']; $offset++) {
                $channel->clear();
                
                for ($i = $offset; $i < $count; $i += $opts['spacing']) {
                    $channel->setPixelColorHex($i, $opts['color']);
                }
                
                $channel->show();
                usleep($opts['speed_ms'] * 1000);
            }
        }
        
        $channel->clear()->show();
    }
}

