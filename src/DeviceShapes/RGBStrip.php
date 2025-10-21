<?php

namespace PhpdaFruit\NeoPixels\DeviceShapes;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

/**
 * RGBStrip - For linear LED strips with advanced animation methods
 */
class RGBStrip extends PixelChannel
{
    /**
     * Whether to reverse pixel indexing (useful for upside-down mounting)
     */
    protected bool $reverse_pixels = false;

    public function __construct(
        int $num_pixels,
        string $device_path = '/dev/spidev0.0',
        NeoPixelType $neopixel_type = NeoPixelType::RGB
    ) {
        parent::__construct($num_pixels, $device_path, $neopixel_type);
    }

    /**
     * Flip/reverse the pixel order (toggle)
     * Useful when strip is mounted upside down
     *
     * @return static
     */
    public function flip(): static
    {
        $this->reverse_pixels = !$this->reverse_pixels;
        return $this;
    }

    /**
     * Set whether pixels should be reversed
     *
     * @param bool $reversed
     * @return static
     */
    public function setReversed(bool $reversed): static
    {
        $this->reverse_pixels = $reversed;
        return $this;
    }

    /**
     * Check if pixels are reversed
     *
     * @return bool
     */
    public function isReversed(): bool
    {
        return $this->reverse_pixels;
    }

    /**
     * Translate pixel index if reversed
     *
     * @param int $pixel
     * @return int
     */
    protected function translatePixelIndex(int $pixel): int
    {
        if ($this->reverse_pixels) {
            return $this->getPixelCount() - 1 - $pixel;
        }
        return $pixel;
    }

    /**
     * Override setPixelColorHex to handle reversal
     *
     * @param int $pixel
     * @param int $color
     * @return void
     */
    public function setPixelColorHex(int $pixel, int $color): void
    {
        parent::setPixelColorHex($this->translatePixelIndex($pixel), $color);
    }

    /**
     * Override getPixelColor to handle reversal
     *
     * @param int $pixel
     * @return int
     */
    public function getPixelColor(int $pixel): int
    {
        return parent::getPixelColor($this->translatePixelIndex($pixel));
    }

    /**
     * Fill with a gradient between two colors
     *
     * @param int $startColor Starting color
     * @param int $endColor Ending color
     * @return static
     */
    public function fillGradient(int $startColor, int $endColor): static
    {
        $count = $this->getPixelCount();
        
        for ($i = 0; $i < $count; $i++) {
            $t = $i / ($count - 1);
            $color = $this->lerpColor($startColor, $endColor, $t);
            $this->setPixelColorHex($i, $color);
        }
        
        return $this;
    }

    /**
     * Color wave animation
     *
     * @param int $color Wave color
     * @param int $waves Number of waves
     * @param int $speed_ms Time per wave
     * @return static
     */
    public function wave(int $color = 0xFFFFFF, int $waves = 3, int $speed_ms = 1000): static
    {
        $count = $this->getPixelCount();
        $steps = $count * 2;
        $delay = (int)(($speed_ms * 1000) / $steps);
        
        for ($wave = 0; $wave < $waves; $wave++) {
            for ($step = 0; $step < $steps; $step++) {
                $this->clear();
                
                // Create wave effect
                for ($i = 0; $i < $count; $i++) {
                    $distance = abs($i - $step);
                    if ($distance < 3) {
                        $brightness = 1.0 - ($distance / 3.0);
                        $dimmedColor = $this->dimColor($color, $brightness);
                        $this->setPixelColorHex($i, $dimmedColor);
                    }
                }
                
                $this->show();
                usleep($delay);
            }
        }
        
        $this->clear()->show();
        return $this;
    }

    /**
     * Random sparkle effect
     *
     * @param int $color Sparkle color
     * @param int $duration_ms Duration
     * @param int $density How many pixels sparkle (1-10)
     * @return static
     */
    public function sparkle(int $color = 0xFFFFFF, int $duration_ms = 2000, int $density = 3): static
    {
        $count = $this->getPixelCount();
        $iterations = (int)($duration_ms / 50);
        
        for ($i = 0; $i < $iterations; $i++) {
            // Random pixels sparkle
            for ($d = 0; $d < $density; $d++) {
                $pixel = rand(0, $count - 1);
                $this->setPixelColorHex($pixel, $color);
            }
            
            $this->show();
            usleep(50000);
            
            // Dim down
            for ($p = 0; $p < $count; $p++) {
                $currentColor = $this->getPixelColor($p);
                if ($currentColor > 0) {
                    $dimmed = $this->dimColor($currentColor, 0.7);
                    $this->setPixelColorHex($p, $dimmed);
                }
            }
        }
        
        $this->clear()->show();
        return $this;
    }

    /**
     * Expand from center outward
     *
     * @param int $color Expansion color
     * @param int $speed_ms Duration
     * @return static
     */
    public function centerOut(int $color = 0xFFFFFF, int $speed_ms = 1000): static
    {
        $count = $this->getPixelCount();
        $center = (int)($count / 2);
        $steps = $center + 1;
        $delay = (int)(($speed_ms * 1000) / $steps);
        
        for ($i = 0; $i <= $center; $i++) {
            if ($center - $i >= 0) {
                $this->setPixelColorHex($center - $i, $color);
            }
            if ($center + $i < $count) {
                $this->setPixelColorHex($center + $i, $color);
            }
            $this->show();
            usleep($delay);
        }
        
        return $this;
    }

    /**
     * Collapse from edges to center
     *
     * @param int $color Collapse color
     * @param int $speed_ms Duration
     * @return static
     */
    public function edgesIn(int $color = 0xFFFFFF, int $speed_ms = 1000): static
    {
        $count = $this->getPixelCount();
        $steps = (int)($count / 2) + 1;
        $delay = (int)(($speed_ms * 1000) / $steps);
        
        $this->clear();
        
        for ($i = 0; $i < $steps; $i++) {
            if ($i < $count) {
                $this->setPixelColorHex($i, $color);
                $this->setPixelColorHex($count - 1 - $i, $color);
            }
            $this->show();
            usleep($delay);
        }
        
        return $this;
    }

    /**
     * Theater chase effect
     *
     * @param int $color Chase color
     * @param int $spacing Spacing between lit pixels
     * @param int $cycles Number of cycles
     * @param int $speed_ms Speed
     * @return static
     */
    public function theaterChase(int $color = 0xFFFFFF, int $spacing = 3, int $cycles = 10, int $speed_ms = 50): static
    {
        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            for ($offset = 0; $offset < $spacing; $offset++) {
                $this->clear();
                
                for ($i = $offset; $i < $this->getPixelCount(); $i += $spacing) {
                    $this->setPixelColorHex($i, $color);
                }
                
                $this->show();
                usleep($speed_ms * 1000);
            }
        }
        
        $this->clear()->show();
        return $this;
    }

    /**
     * Rainbow pattern
     *
     * @return static
     */
    public function rainbow(): static
    {
        $count = $this->getPixelCount();
        
        for ($i = 0; $i < $count; $i++) {
            $hue = (int)(($i / $count) * 255);
            $color = $this->wheel($hue);
            $this->setPixelColorHex($i, $color);
        }
        
        return $this;
    }

    /**
     * Animated rainbow cycle
     *
     * @param int $cycles Number of cycles
     * @param int $speed_ms Speed per cycle
     * @return static
     */
    public function rainbowCycle(int $cycles = 5, int $speed_ms = 50): static
    {
        $count = $this->getPixelCount();
        
        for ($cycle = 0; $cycle < $cycles * 256; $cycle++) {
            for ($i = 0; $i < $count; $i++) {
                $color = $this->wheel((int)((($i * 256 / $count) + $cycle)) & 255);
                $this->setPixelColorHex($i, $color);
            }
            $this->show();
            usleep($speed_ms * 1000);
        }
        
        return $this;
    }

    /**
     * Fire effect simulation
     *
     * @param int $duration_ms Duration
     * @return static
     */
    public function fire(int $duration_ms = 3000): static
    {
        $count = $this->getPixelCount();
        $iterations = (int)($duration_ms / 50);
        
        for ($i = 0; $i < $iterations; $i++) {
            for ($pixel = 0; $pixel < $count; $pixel++) {
                $heat = rand(80, 255);
                $r = $heat;
                $g = (int)($heat * 0.4);
                $b = 0;
                
                $color = ($r << 16) | ($g << 8) | $b;
                $this->setPixelColorHex($pixel, $color);
            }
            
            $this->show();
            usleep(50000);
        }
        
        return $this;
    }

    /**
     * Comet/shooting star effect
     *
     * @param int $color Comet color
     * @param int $comets Number of comets
     * @param int $tail_length Length of tail
     * @return static
     */
    public function comet(int $color = 0xFFFFFF, int $comets = 3, int $tail_length = 5): static
    {
        $count = $this->getPixelCount();
        
        for ($c = 0; $c < $comets; $c++) {
            for ($pos = 0; $pos < $count + $tail_length; $pos++) {
                $this->clear();
                
                // Draw tail
                for ($t = 0; $t < $tail_length; $t++) {
                    $pixel = $pos - $t;
                    if ($pixel >= 0 && $pixel < $count) {
                        $brightness = 1.0 - ($t / $tail_length);
                        $dimmedColor = $this->dimColor($color, $brightness);
                        $this->setPixelColorHex($pixel, $dimmedColor);
                    }
                }
                
                $this->show();
                usleep(30000);
            }
        }
        
        $this->clear()->show();
        return $this;
    }

    /**
     * Helper: Color wheel (0-255 to RGB color)
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

    /**
     * Helper: Interpolate between colors
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