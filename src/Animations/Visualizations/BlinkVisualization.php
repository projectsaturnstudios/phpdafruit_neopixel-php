<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * BlinkVisualization
 * 
 * Simple on/off blinking effect
 */
class BlinkVisualization extends RGBVisualization
{
    use TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::BLINK;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0xFFFFFF,      // White blink
            'blink_speed_ms' => 500,  // Speed per blink cycle
            'duty_cycle' => 0.5,      // On time ratio (0.5 = 50% on, 50% off)
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        
        $blinks = (int)($duration_ms / $opts['blink_speed_ms']);
        $onTime = (int)($opts['blink_speed_ms'] * $opts['duty_cycle']);
        $offTime = $opts['blink_speed_ms'] - $onTime;
        
        for ($i = 0; $i < $blinks; $i++) {
            // On
            $channel->fill($opts['color']);
            $channel->show();
            $this->wait($onTime);
            
            // Off
            $channel->clear();
            $channel->show();
            $this->wait($offTime);
        }
        
        $channel->clear()->show();
    }
}

