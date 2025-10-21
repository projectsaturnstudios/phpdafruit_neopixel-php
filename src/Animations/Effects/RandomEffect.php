<?php

namespace PhpdaFruit\NeoPixels\Animations\Effects;

/**
 * RandomEffect Trait
 * 
 * Provides random color and value generation for animations
 */
trait RandomEffect
{
    /**
     * Generate a random RGB color
     * 
     * @param int $min_brightness Minimum brightness per channel (0-255)
     * @param int $max_brightness Maximum brightness per channel (0-255)
     * @return int Random RGB color
     */
    protected function randomColor(int $min_brightness = 0, int $max_brightness = 255): int
    {
        $r = rand($min_brightness, $max_brightness);
        $g = rand($min_brightness, $max_brightness);
        $b = rand($min_brightness, $max_brightness);
        
        return ($r << 16) | ($g << 8) | $b;
    }

    /**
     * Generate a random color from a predefined palette
     * 
     * @param array<int> $palette Array of color values
     * @return int Random color from palette
     */
    protected function randomColorFromPalette(array $palette): int
    {
        if (empty($palette)) {
            return $this->randomColor();
        }
        
        return $palette[array_rand($palette)];
    }

    /**
     * Generate a random primary color (red, green, or blue)
     * 
     * @param int $brightness Brightness level (0-255)
     * @return int Random primary color
     */
    protected function randomPrimaryColor(int $brightness = 255): int
    {
        $colors = [
            $brightness << 16,  // Red
            $brightness << 8,   // Green
            $brightness,        // Blue
        ];
        
        return $colors[array_rand($colors)];
    }

    /**
     * Generate a random pastel color (soft, lighter colors)
     * 
     * @return int Random pastel color
     */
    protected function randomPastelColor(): int
    {
        // Pastel colors have high RGB values with slight variations
        $r = rand(150, 255);
        $g = rand(150, 255);
        $b = rand(150, 255);
        
        return ($r << 16) | ($g << 8) | $b;
    }

    /**
     * Generate a random vibrant color (high saturation)
     * 
     * @return int Random vibrant color
     */
    protected function randomVibrantColor(): int
    {
        // Vibrant colors have at least one channel at max and one at min
        $channels = [255, rand(0, 128), 0];
        shuffle($channels);
        
        return ($channels[0] << 16) | ($channels[1] << 8) | $channels[2];
    }

    /**
     * Generate an array of random colors
     * 
     * @param int $count Number of colors to generate
     * @param int $min_brightness Minimum brightness per channel
     * @param int $max_brightness Maximum brightness per channel
     * @return array<int> Array of random colors
     */
    protected function generateRandomColors(int $count, int $min_brightness = 0, int $max_brightness = 255): array
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $this->randomColor($min_brightness, $max_brightness);
        }
        return $colors;
    }

    /**
     * Generate a random pixel position within range
     * 
     * @param int $min Minimum position
     * @param int $max Maximum position
     * @return int Random position
     */
    protected function randomPixel(int $min, int $max): int
    {
        return rand($min, $max);
    }

    /**
     * Randomly choose between two values based on probability
     * 
     * @param mixed $value1 First value
     * @param mixed $value2 Second value
     * @param float $probability Probability of choosing value1 (0.0-1.0)
     * @return mixed Chosen value
     */
    protected function randomChoice($value1, $value2, float $probability = 0.5)
    {
        return (rand(0, 100) / 100) < $probability ? $value1 : $value2;
    }

    /**
     * Generate a random float between min and max
     * 
     * @param float $min Minimum value
     * @param float $max Maximum value
     * @return float Random float
     */
    protected function randomFloat(float $min = 0.0, float $max = 1.0): float
    {
        return $min + (rand() / getrandmax()) * ($max - $min);
    }
}

