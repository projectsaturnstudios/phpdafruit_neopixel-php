<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\ColorWheelEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * ChaseRainbowWhiteVisualization
 * 
 * Theater chase with rainbow colors and white gaps between
 */
class ChaseRainbowWhiteVisualization extends RGBVisualization
{
    use TimingEffect, ColorWheelEffect;

    public function getAnimationType(): Animation
    {
        return Animation::CHASE_RAINBOW_WHITE;
    }

    public function getDefaultOptions(): array
    {
        return [
            'speed_ms' => 100,          // Speed of chase
            'group_size' => 3,          // Pixels in each group
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        $step = 0;
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts, &$step) {
            for ($i = 0; $i < $count; $i++) {
                $position = ($i + $step) % $opts['group_size'];
                
                if ($position == 0) {
                    // Rainbow color based on pixel position
                    $hue = (($i * 256) / $count + $step * 2) % 256;
                    $color = $this->wheel((int)$hue);
                    $channel->setPixelColorHex($i, $color);
                } else {
                    // White gaps
                    $channel->setPixelColorHex($i, 0xFFFFFF);
                }
            }
            
            $channel->show();
            $step++;
            
            return $opts['speed_ms'];
        });
    }
}

