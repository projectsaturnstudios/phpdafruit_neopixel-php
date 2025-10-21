<?php

namespace PhpdaFruit\NeoPixels\DeviceShapes;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

/**
 * DoubleDots - Perfect for 2-LED devices like RGB fans, dual indicators
 */
class DoubleDots extends PixelChannel
{
    public function __construct(
        protected string $path = '/dev/spidev0.0',
        NeoPixelType $type = NeoPixelType::RGB
    ) {
        parent::__construct(2, $path, $type);
    }

    /**
     * Set left LED color
     *
     * @param int $color Hex color
     * @return static
     */
    public function setLeft(int $color): static
    {
        $this->setPixelColorHex(0, $color);
        return $this;
    }

    /**
     * Set right LED color
     *
     * @param int $color Hex color
     * @return static
     */
    public function setRight(int $color): static
    {
        $this->setPixelColorHex(1, $color);
        return $this;
    }

    /**
     * Set both LEDs to the same color
     *
     * @param int $color Hex color
     * @return static
     */
    public function mirror(int $color): static
    {
        $this->fill($color);
        return $this;
    }

    /**
     * Set both LEDs to different colors
     *
     * @param int $leftColor Left LED hex color
     * @param int $rightColor Right LED hex color
     * @return static
     */
    public function split(int $leftColor, int $rightColor): static
    {
        $this->setLeft($leftColor)->setRight($rightColor);
        return $this;
    }

    /**
     * Set complementary colors on each LED
     *
     * @param int $color Base color
     * @return static
     */
    public function opposite(int $color): static
    {
        // Calculate complementary color by inverting RGB
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;
        
        $complement = ((255 - $r) << 16) | ((255 - $g) << 8) | (255 - $b);
        
        $this->split($color, $complement);
        return $this;
    }

    /**
     * Alternate between the two LEDs
     *
     * @param int $color Hex color
     * @param int $cycles Number of alternations
     * @param int $delay_ms Delay between switches
     * @return static
     */
    public function alternate(int $color = 0xFFFFFF, int $cycles = 5, int $delay_ms = 200): static
    {
        for ($i = 0; $i < $cycles; $i++) {
            $this->setLeft($color)->setRight(0x000000)->show();
            usleep($delay_ms * 1000);
            
            $this->setLeft(0x000000)->setRight($color)->show();
            usleep($delay_ms * 1000);
        }
        
        $this->clear()->show();
        return $this;
    }

    /**
     * Simulate spinning effect
     *
     * @param int $color Hex color
     * @param int $rotations Number of rotations
     * @param int $speed_ms Time per rotation
     * @return static
     */
    public function spin(int $color = 0xFFFFFF, int $rotations = 3, int $speed_ms = 500): static
    {
        $steps = 4; // Quarter turn steps
        $delay = (int)(($speed_ms * 1000) / $steps);
        
        for ($rot = 0; $rot < $rotations; $rot++) {
            // Full left
            $this->setLeft($color)->setRight(0x000000)->show();
            usleep($delay);
            
            // Both
            $this->mirror($color)->show();
            usleep($delay);
            
            // Full right
            $this->setLeft(0x000000)->setRight($color)->show();
            usleep($delay);
            
            // Off
            $this->clear()->show();
            usleep($delay);
        }
        
        return $this;
    }

    /**
     * Ping-pong effect between LEDs
     *
     * @param int $color Hex color
     * @param int $bounces Number of bounces
     * @param int $delay_ms Delay between bounces
     * @return static
     */
    public function bounce(int $color = 0xFFFFFF, int $bounces = 5, int $delay_ms = 150): static
    {
        for ($i = 0; $i < $bounces; $i++) {
            // Left to right
            $this->setLeft($color)->setRight(0x000000)->show();
            usleep($delay_ms * 1000);
            
            // Right to left
            $this->setLeft(0x000000)->setRight($color)->show();
            usleep($delay_ms * 1000);
        }
        
        $this->clear()->show();
        return $this;
    }

    /**
     * Fade between two colors, each LED showing one
     *
     * @param int $color1 First color
     * @param int $color2 Second color
     * @param int $cycles Number of fade cycles
     * @param int $duration_ms Duration per cycle
     * @return static
     */
    public function crossfade(int $color1, int $color2, int $cycles = 2, int $duration_ms = 1000): static
    {
        $steps = 50;
        $delay = (int)(($duration_ms * 1000) / $steps);
        
        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            // Fade from color1/color2 to color2/color1
            for ($i = 0; $i <= $steps; $i++) {
                $t = $i / $steps;
                
                // Left fades from color1 to color2
                $leftColor = $this->lerpColor($color1, $color2, $t);
                // Right fades from color2 to color1
                $rightColor = $this->lerpColor($color2, $color1, $t);
                
                $this->setLeft($leftColor)->setRight($rightColor)->show();
                usleep($delay);
            }
        }
        
        return $this;
    }

    /**
     * Helper to interpolate between two colors
     */
    private function lerpColor(int $color1, int $color2, float $t): int
    {
        $r1 = ($color1 >> 16) & 0xFF;
        $g1 = ($color1 >> 8) & 0xFF;
        $b1 = $color1 & 0xFF;
        
        $r2 = ($color2 >> 16) & 0xFF;
        $g2 = ($color2 >> 8) & 0xFF;
        $b2 = $color2 & 0xFF;
        
        $r = (int)($r1 + ($r2 - $r1) * $t);
        $g = (int)($g1 + ($g2 - $g1) * $t);
        $b = (int)($b1 + ($b2 - $b1) * $t);
        
        return ($r << 16) | ($g << 8) | $b;
    }
}