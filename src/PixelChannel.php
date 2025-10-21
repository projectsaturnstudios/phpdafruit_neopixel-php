<?php

namespace Phpdafruit\NeoPixels;

use NeoPixel;
use PhpdaFruit\NeoPixels\Contracts\WS821x as WS821xContract;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

class PixelChannel implements WS821xContract
{
    protected ?NeoPixel $neo_pixel = null;

    public function __construct(
        protected int $num_pixels = 1,
        protected string $device_path = '/dev/spidev0.0',
        protected NeoPixelType $neopixel_type = NeoPixelType::RGB
    ) {
        $this->neo_pixel = new NeoPixel($this->num_pixels, $this->device_path, $this->neopixel_type->value);
        $this->neo_pixel->begin();
    }

    public function __destruct()
    {
        // Clear pixels before cleanup to avoid visual artifacts
        if ($this->neo_pixel !== null) {
            try {
                $this->neo_pixel->clear();
                $this->neo_pixel->show();
            } catch (\Throwable $e) {
                // Silently ignore errors during cleanup
            }
            $this->neo_pixel = null;
        }
    }


    public function begin(): void
    {
        $this->neo_pixel->begin();
    }

    public function show(): static
    {
        $this->neo_pixel->show();
        return $this;
    }

    public function updateLength(int $n): static
    {
        $this->neo_pixel->updateLength($n);
        return $this;
    }

    public function updateType(NeoPixelType $type): static
    {
        $this->neo_pixel->updateType($type->value);
        return $this;
    }

    public function setDevicePath(string $path): static
    {
        $this->neo_pixel->setDevicePath($path);
        return $this;
    }

    public function setPixelColor(int $n, int $r, int $g, int $b, int $w = 0): void
    {
        $this->neo_pixel->setPixelColor($n, $r, $g, $b, $w);
    }

    public function fill(int $color, int $first = 0, int $count = 0): static
    {
        $this->neo_pixel->fill($color, $first, $count);
        return $this;
    }

    public function getPixelColor(int $n): int
    {
        return $this->neo_pixel->getPixelColor($n);
    }

    public function setBrightness(int $b): void
    {
        $this->neo_pixel->setBrightness($b);
    }

    public function getBrightness(): int
    {
        return $this->neo_pixel->getBrightness();
    }

    public function clear(): static
    {
        $this->neo_pixel->clear();
        return $this;
    }

    /**
     * Get the number of pixels in this channel
     *
     * @return int
     */
    public function getPixelCount(): int
    {
        return $this->neo_pixel->getPixelCount();
    }

    /**
     * Set pixel color using a hex color value
     *
     * @param int $pixel Pixel index
     * @param int $hexColor Color in 0xRRGGBB or 0xWWRRGGBB format
     * @return void
     */
    public function setPixelColorHex(int $pixel, int $hexColor): void
    {
        $w = ($hexColor >> 24) & 0xFF;
        $r = ($hexColor >> 16) & 0xFF;
        $g = ($hexColor >> 8) & 0xFF;
        $b = $hexColor & 0xFF;
        
        $this->neo_pixel->setPixelColor($pixel, $r, $g, $b, $w);
    }

    /**
     * Get all pixel colors as an array
     *
     * @return array<int, int> Array of pixel colors in hex format
     */
    public function getPixels(): array
    {
        $pixels = [];
        $count = $this->getPixelCount();
        
        for ($i = 0; $i < $count; $i++) {
            $pixels[$i] = $this->neo_pixel->getPixelColor($i);
        }
        
        return $pixels;
    }

    /**
     * Rotate pixel colors by a number of positions
     *
     * @param int $positions Number of positions to rotate (positive = right, negative = left)
     * @return static
     */
    public function rotate(int $positions = 1): static
    {
        $count = $this->getPixelCount();
        if ($count <= 1) {
            return $this;
        }
        
        // Normalize positions to be within range
        $positions = $positions % $count;
        if ($positions < 0) {
            $positions += $count;
        }
        
        if ($positions === 0) {
            return $this;
        }
        
        // Get all current colors
        $colors = $this->getPixels();
        
        // Rotate the array
        $rotated = array_merge(
            array_slice($colors, -$positions),
            array_slice($colors, 0, -$positions)
        );
        
        // Set the rotated colors back
        foreach ($rotated as $i => $color) {
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;
            $w = ($color >> 24) & 0xFF;
            $this->neo_pixel->setPixelColor($i, $r, $g, $b, $w);
        }
        
        return $this;
    }

    /**
     * Reverse the order of pixel colors
     *
     * @return static
     */
    public function reverse(): static
    {
        $colors = array_reverse($this->getPixels());
        
        foreach ($colors as $i => $color) {
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;
            $w = ($color >> 24) & 0xFF;
            $this->neo_pixel->setPixelColor($i, $r, $g, $b, $w);
        }
        
        return $this;
    }

    /**
     * Fade in from current brightness to full brightness
     *
     * @param int $duration_ms Duration in milliseconds
     * @return static
     */
    public function fadeIn(int $duration_ms = 1000): static
    {
        $startBrightness = $this->neo_pixel->getBrightness();
        $steps = max(1, (int)($duration_ms / 20)); // ~50fps
        $delay = (int)($duration_ms * 1000 / $steps); // Convert to microseconds
        
        for ($i = 0; $i <= $steps; $i++) {
            $brightness = $startBrightness + (int)((255 - $startBrightness) * ($i / $steps));
            $this->neo_pixel->setBrightness($brightness);
            $this->neo_pixel->show();
            usleep($delay);
        }
        
        return $this;
    }

    /**
     * Fade out from current brightness to off
     *
     * @param int $duration_ms Duration in milliseconds
     * @return static
     */
    public function fadeOut(int $duration_ms = 1000): static
    {
        $startBrightness = $this->neo_pixel->getBrightness();
        $steps = max(1, (int)($duration_ms / 20)); // ~50fps
        $delay = (int)($duration_ms * 1000 / $steps); // Convert to microseconds
        
        for ($i = 0; $i <= $steps; $i++) {
            $brightness = $startBrightness - (int)($startBrightness * ($i / $steps));
            $this->neo_pixel->setBrightness($brightness);
            $this->neo_pixel->show();
            usleep($delay);
        }
        
        return $this;
    }
}