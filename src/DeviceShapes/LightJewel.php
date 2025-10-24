<?php

namespace PhpdaFruit\NeoPixels\DeviceShapes;

use PhpdaFruit\NeoPixels\Concerns\DeviceShapes\VirtualPixelMapping;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use Phpdafruit\NeoPixels\PixelChannel;

/**
 * LightJewel - Adafruit NeoPixel Jewel (7 LEDs)
 * 
 * Physical wiring: Center LED at index 0, ring LEDs at 1-6 (clockwise from top)
 * Virtual mapping: Ring LEDs at 0-5, center LED at index 6
 * 
 * This mapping makes animations more intuitive:
 * - spinRing() only needs to iterate 0-5
 * - fillRing() doesn't need to skip index 0
 * - setCenter() is clearly index 6
 */
class LightJewel extends PixelChannel
{
    use VirtualPixelMapping;

    public function __construct(
        protected string $path = '/dev/spidev0.0',
        NeoPixelType $type = NeoPixelType::RGB
    ) {
        parent::__construct(7, $path, $type);
        
        /**
         * Virtual pixel mapping:
         * Logical 0-5 = Ring LEDs (physical 1-6)
         * Logical 6   = Center LED (physical 0)
         */
        $this->pixel_map = [1, 2, 3, 4, 5, 6, 0];
    }

    /**
     * Set the center LED color
     * 
     * @param int $color Hex color
     * @return static
     */
    public function setCenter(int $color): static
    {
        $this->setPixelColorHex(6, $color);
        return $this;
    }

    /**
     * Set all ring LEDs to the same color
     * 
     * @param int $color Hex color
     * @return static
     */
    public function fillRing(int $color): static
    {
        for ($i = 0; $i < 6; $i++) {
            $this->setPixelColorHex($i, $color);
        }
        return $this;
    }

    /**
     * Set ring LED at specific position (0-5)
     * 
     * @param int $position Ring position (0 = top, clockwise)
     * @param int $color Hex color
     * @return static
     */
    public function setRingPixel(int $position, int $color): static
    {
        if ($position < 0 || $position > 5) {
            throw new \OutOfBoundsException("Ring position must be 0-5, got {$position}");
        }
        $this->setPixelColorHex($position, $color);
        return $this;
    }

    /**
     * Spin effect around the ring
     * 
     * @param int $color Pixel color
     * @param int $rotations Number of full rotations
     * @param int $speed_ms Time per step
     * @return static
     */
    public function spinRing(int $color = 0xFFFFFF, int $rotations = 3, int $speed_ms = 100): static
    {
        for ($rot = 0; $rot < $rotations * 6; $rot++) {
            $this->fillRing(0x000000);
            $pixel = $rot % 6;
            $this->setPixelColorHex($pixel, $color);
            $this->show();
            usleep($speed_ms * 1000);
        }
        
        return $this;
    }

    /**
     * Chase effect around the ring
     * 
     * @param int $color Chase color
     * @param int $tail_length Number of trailing pixels
     * @param int $cycles Number of cycles
     * @param int $speed_ms Speed per step
     * @return static
     */
    public function chaseRing(int $color = 0xFFFFFF, int $tail_length = 3, int $cycles = 3, int $speed_ms = 80): static
    {
        for ($cycle = 0; $cycle < $cycles * 6; $cycle++) {
            $this->fillRing(0x000000);
            
            for ($t = 0; $t < $tail_length; $t++) {
                $pos = ($cycle - $t + 6) % 6;
                $brightness = 1.0 - ($t / $tail_length);
                $dimmedColor = $this->dimColor($color, $brightness);
                $this->setPixelColorHex($pos, $dimmedColor);
            }
            
            $this->show();
            usleep($speed_ms * 1000);
        }
        
        $this->fillRing(0x000000)->show();
        return $this;
    }

    /**
     * Pulse the center LED
     * 
     * @param int $color Center color
     * @param int $cycles Number of pulses
     * @param int $duration_ms Duration per pulse
     * @return static
     */
    public function pulseCenter(int $color = 0xFFFFFF, int $cycles = 2, int $duration_ms = 1000): static
    {
        $this->setCenter($color);
        
        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            // Fade in
            for ($b = 0; $b <= 255; $b += 5) {
                $dimmed = $this->dimColor($color, $b / 255.0);
                $this->setCenter($dimmed)->show();
                usleep((int)(($duration_ms * 1000 / 2) / 51)); // 51 steps
            }
            
            // Fade out
            for ($b = 255; $b >= 0; $b -= 5) {
                $dimmed = $this->dimColor($color, $b / 255.0);
                $this->setCenter($dimmed)->show();
                usleep((int)(($duration_ms * 1000 / 2) / 51));
            }
        }
        
        return $this;
    }

    /**
     * Expand from center to ring
     * 
     * @param int $centerColor Center LED color
     * @param int $ringColor Ring color
     * @param int $speed_ms Duration
     * @return static
     */
    public function expandOut(int $centerColor = 0xFFFFFF, int $ringColor = 0xFFFFFF, int $speed_ms = 1000): static
    {
        // Light center
        $this->clear();
        $this->setCenter($centerColor)->show();
        usleep((int)($speed_ms * 1000 / 7));
        
        // Expand to ring
        for ($i = 0; $i < 6; $i++) {
            $this->setPixelColorHex($i, $ringColor);
            $this->show();
            usleep((int)($speed_ms * 1000 / 7));
        }
        
        return $this;
    }

    /**
     * Collapse from ring to center
     * 
     * @param int $finalColor Final center color
     * @param int $speed_ms Duration
     * @return static
     */
    public function collapseIn(int $finalColor = 0xFFFFFF, int $speed_ms = 1000): static
    {
        // Turn off ring pixels one by one
        for ($i = 5; $i >= 0; $i--) {
            $this->setPixelColorHex($i, 0x000000);
            $this->show();
            usleep((int)($speed_ms * 1000 / 7));
        }
        
        // Keep center lit
        $this->setCenter($finalColor)->show();
        usleep((int)($speed_ms * 1000 / 7));
        
        return $this;
    }

    /**
     * Rainbow around the ring
     * 
     * @return static
     */
    public function rainbowRing(): static
    {
        for ($i = 0; $i < 6; $i++) {
            $hue = (int)(($i / 6) * 255);
            $color = $this->wheel($hue);
            $this->setPixelColorHex($i, $color);
        }
        
        return $this;
    }

    /**
     * Helper: Color wheel for rainbow effects
     */
    private function wheel(int $pos): int
    {
        $pos = $pos % 256;
        if ($pos < 85) {
            return (($pos * 3) << 16) | ((255 - $pos * 3) << 8);
        } elseif ($pos < 170) {
            $pos -= 85;
            return ((255 - $pos * 3) << 16) | (($pos * 3) << 0);
        } else {
            $pos -= 170;
            return (($pos * 3) << 8) | (255 - $pos * 3);
        }
    }

    /**
     * Helper: Dim a color by a factor
     */
    private function dimColor(int $color, float $factor): int
    {
        $r = (int)((($color >> 16) & 0xFF) * $factor);
        $g = (int)((($color >> 8) & 0xFF) * $factor);
        $b = (int)(($color & 0xFF) * $factor);
        return ($r << 16) | ($g << 8) | $b;
    }
}
