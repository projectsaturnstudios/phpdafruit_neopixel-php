<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\Animations\Effects\ColorWheelEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * ChaseBlackoutRainbowVisualization
 * 
 * Rainbow theater chase with blackout between steps
 */
class ChaseBlackoutRainbowVisualization extends RGBVisualization
{
    use TimingEffect, ColorWheelEffect;

    public function getAnimationType(): Animation
    {
        return Animation::CHASE_BLACKOUT_RAINBOW;
    }

    public function getDefaultOptions(): array
    {
        return [
            'speed_ms' => 150,          // Speed of chase
            'group_size' => 3,          // Pixels in each group
            'blackout_ms' => 75,        // Duration of blackout
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        $step = 0;
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts, &$step) {
            // Show rainbow chase pattern
            $channel->clear();
            
            for ($i = 0; $i < $count; $i++) {
                if (($i + $step) % $opts['group_size'] == 0) {
                    $hue = (($i * 256) / $count + $step * 2) % 256;
                    $color = $this->wheel((int)$hue);
                    $channel->setPixelColorHex($i, $color);
                }
            }
            
            $channel->show();
            usleep($opts['speed_ms'] * 1000);
            
            // Blackout
            $channel->clear()->show();
            usleep($opts['blackout_ms'] * 1000);
            
            $step++;
            
            return 0; // We handled timing manually
        });
    }
}

