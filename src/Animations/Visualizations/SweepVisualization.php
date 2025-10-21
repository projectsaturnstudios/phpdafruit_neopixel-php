<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Animations\Effects\TimingEffect;

/**
 * SweepVisualization
 * 
 * Single LED sweeping back and forth
 */
class SweepVisualization extends RGBVisualization
{
    use TimingEffect;

    public function getAnimationType(): Animation
    {
        return Animation::SWEEP;
    }

    public function getDefaultOptions(): array
    {
        return [
            'color' => 0x00FF00,      // Green by default
            'speed_ms' => 30,         // Speed per pixel
            'trail' => false,         // Leave trail behind
        ];
    }

    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        $opts = array_merge($this->getDefaultOptions(), $options);
        $count = $channel->getPixelCount();
        
        $timer = $this->createTimer($duration_ms);
        
        while (!$timer->isComplete()) {
            // Sweep right
            for ($pos = 0; $pos < $count && !$timer->isComplete(); $pos++) {
                if (!$opts['trail']) {
                    $channel->clear();
                }
                $channel->setPixelColorHex($pos, $opts['color']);
                $channel->show();
                $this->wait($opts['speed_ms']);
            }
            
            // Sweep left
            for ($pos = $count - 1; $pos >= 0 && !$timer->isComplete(); $pos--) {
                if (!$opts['trail']) {
                    $channel->clear();
                }
                $channel->setPixelColorHex($pos, $opts['color']);
                $channel->show();
                $this->wait($opts['speed_ms']);
            }
        }
        
        $channel->clear()->show();
    }
}

