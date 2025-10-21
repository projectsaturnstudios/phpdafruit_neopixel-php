<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * ChaseFlashVisualization
 * 
 * Theater chase with periodic full flash
 * Classic chase pattern with dramatic flash moments
 */
class ChaseFlashVisualization extends RGBVisualization
{
    use TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::CHASE_FLASH;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFF0000,        // Chase color
            'speed_ms' => 100,          // Speed of chase
            'group_size' => 3,          // Pixels in each group
            'flash_interval' => 10,     // Flash every N steps
            'flash_color' => 0xFFFFFF,  // White flash
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        $step = 0;
        
        $this->runForDuration($duration_ms, function() use ($channel, $count, $opts, &$step) {
            // Flash moment
            if ($step % $opts['flash_interval'] == 0) {
                $channel->fill($opts['flash_color'])->show();
                usleep(50000); // 50ms flash
                $step++;
                return 50;
            }
            
            // Regular chase
            $channel->clear();
            
            for ($i = 0; $i < $count; $i++) {
                if (($i + $step) % $opts['group_size'] == 0) {
                    $channel->setPixelColorHex($i, $opts['color']);
                }
            }
            
            $channel->show();
            $step++;
            
            return $opts['speed_ms'];
        });
    }
}

