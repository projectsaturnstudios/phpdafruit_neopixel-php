<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;
use PhpdaFruit\NeoPixels\Animations\Effects\FadeEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * MeteorShowerVisualization
 * 
 * Creates a meteor shower effect with multiple meteors falling
 */
class MeteorShowerVisualization extends RGBVisualization
{
    use FadeEffect, TimingEffect;
    public function getAnimationType(): Animation
    {
        return Animation::METEOR_SHOWER;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,      // White meteors by default
            'meteor_count' => 5,      // Number of meteors
            'tail_length' => 8,       // Length of meteor tail
            'speed_ms' => 50,         // Speed between frames
        ];
    }

    public function isCompatible(PixelChannel $channel): bool
    {
        // Works on any 1D strip
        return true;
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $startTime = microtime(true);
        $elapsed = 0;
        
        // Create meteor positions
        $meteors = [];
        for ($i = 0; $i < $opts['meteor_count']; $i++) {
            $meteors[] = [
                'pos' => rand(0, $count - 1),
                'offset' => rand(0, $count), // Stagger start positions
            ];
        }
        
        $frame = 0;
        
        while ($elapsed < $duration_ms) {
            $channel->clear();
            
            // Draw each meteor
            foreach ($meteors as &$meteor) {
                $pos = ($meteor['offset'] + $frame) % ($count + $opts['tail_length']);
                
                // Draw meteor tail
                for ($t = 0; $t < $opts['tail_length']; $t++) {
                    $pixel = $pos - $t;
                    if ($pixel >= 0 && $pixel < $count) {
                        $brightness = 1.0 - ($t / $opts['tail_length']);
                        $dimmedColor = $this->dimColor($opts['color'], $brightness);
                        $channel->setPixelColorHex($pixel, $dimmedColor);
                    }
                }
            }
            
            $channel->show();
            usleep($opts['speed_ms'] * 1000);
            
            $frame++;
            $elapsed = (microtime(true) - $startTime) * 1000;
        }
        
        $channel->clear()->show();
    }
}

