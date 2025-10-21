<?php

namespace PhpdaFruit\NeoPixels\Animations\Effects;

/**
 * ColorWheelEffect Trait
 * 
 * Provides rainbow/hue color generation for rainbow-based animations
 */
trait ColorWheelEffect
{
    /**
     * Convert wheel position (0-255) to RGB color
     * Creates a smooth rainbow gradient
     * 
     * @param int $pos Position on color wheel (0-255)
     * @return int RGB color
     */
    protected function wheel(int $pos): int
    {
        $pos = $pos % 256;
        
        if ($pos < 85) {
            // Red to Green
            return (($pos * 3) << 16) | ((255 - $pos * 3) << 8);
        } elseif ($pos < 170) {
            // Green to Blue
            $pos -= 85;
            return ((255 - $pos * 3) << 16) | (($pos * 3) << 0);
        } else {
            // Blue to Red
            $pos -= 170;
            return (($pos * 3) << 8) | (255 - $pos * 3);
        }
    }

    /**
     * Get color from hue (HSV with full saturation and value)
     * 
     * @param float $hue Hue value (0.0 to 1.0)
     * @return int RGB color
     */
    protected function hueToRgb(float $hue): int
    {
        $hue = fmod($hue, 1.0);
        if ($hue < 0) $hue += 1.0;
        
        $wheelPos = (int)($hue * 255);
        return $this->wheel($wheelPos);
    }

    /**
     * Generate an array of rainbow colors
     * 
     * @param int $count Number of colors to generate
     * @param int $offset Starting position on wheel (0-255)
     * @return array<int> Array of RGB colors
     */
    protected function generateRainbow(int $count, int $offset = 0): array
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $pos = (int)(($i * 256 / $count) + $offset) & 255;
            $colors[] = $this->wheel($pos);
        }
        return $colors;
    }

    /**
     * Get complementary color on the wheel
     * 
     * @param int $color Input color
     * @return int Complementary color (opposite on wheel)
     */
    protected function complementaryColor(int $color): int
    {
        // Extract RGB
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;
        
        // Convert to HSV to find hue
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;
        
        if ($delta == 0) {
            $hue = 0;
        } elseif ($max == $r) {
            $hue = 60 * fmod((($g - $b) / $delta), 6);
        } elseif ($max == $g) {
            $hue = 60 * ((($b - $r) / $delta) + 2);
        } else {
            $hue = 60 * ((($r - $g) / $delta) + 4);
        }
        
        if ($hue < 0) $hue += 360;
        
        // Get complementary (180 degrees opposite)
        $compHue = fmod($hue + 180, 360);
        
        return $this->hueToRgb($compHue / 360);
    }

    /**
     * Rotate a color's hue by degrees
     * 
     * @param int $color Input color
     * @param int $degrees Degrees to rotate (-360 to 360)
     * @return int Rotated color
     */
    protected function rotateHue(int $color, int $degrees): int
    {
        // Extract RGB
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;
        
        // Simple approximation using wheel position
        // Find closest wheel position
        $currentPos = 0;
        $minDist = PHP_INT_MAX;
        
        for ($pos = 0; $pos < 256; $pos++) {
            $testColor = $this->wheel($pos);
            $tr = ($testColor >> 16) & 0xFF;
            $tg = ($testColor >> 8) & 0xFF;
            $tb = $testColor & 0xFF;
            
            $dist = abs($r - $tr) + abs($g - $tg) + abs($b - $tb);
            if ($dist < $minDist) {
                $minDist = $dist;
                $currentPos = $pos;
            }
        }
        
        // Rotate by degrees (256 positions = 360 degrees)
        $newPos = ($currentPos + (int)($degrees * 256 / 360)) & 255;
        return $this->wheel($newPos);
    }
}

