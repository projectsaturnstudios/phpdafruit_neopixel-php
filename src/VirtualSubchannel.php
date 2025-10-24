<?php

namespace PhpdaFruit\NeoPixels;

use Phpdafruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * VirtualSubchannel
 * 
 * Acts as a virtual PixelChannel that operates on a subset of a parent device
 * Each subchannel has its own logical index space (0-based) but maps to
 * specific physical indices on the parent device
 * 
 * Example:
 * - Parent has 7 pixels (0-6)
 * - Subchannel A maps to physical [0] - appears as 1 pixel (index 0)
 * - Subchannel B maps to physical [1,2,3,4,5,6] - appears as 6 pixels (indices 0-5)
 * 
 * All operations (setPixelColor, fill, show) are delegated to the parent
 * but with indices translated to physical positions
 */
class VirtualSubchannel extends PixelChannel
{
    /**
     * Physical pixel indices this subchannel controls
     * 
     * @var array<int>
     */
    protected array $physical_indices;

    /**
     * Parent PixelChannel that owns the physical hardware
     * 
     * @var PixelChannel
     */
    protected PixelChannel $parent;

    /**
     * Create a virtual subchannel
     * 
     * @param PixelChannel $parent Parent device
     * @param array<int> $physical_indices Physical pixel indices this subchannel controls
     */
    public function __construct(PixelChannel $parent, array $physical_indices)
    {
        $this->parent = $parent;
        $this->physical_indices = array_values($physical_indices); // Re-index to 0-based
        
        // DON'T call parent::__construct - we're not creating new hardware
        // Just set the properties PixelChannel would have
        $this->num_pixels = count($this->physical_indices);
    }

    /**
     * Map logical index (0-based for this subchannel) to physical index on parent
     * 
     * @param int $logical Logical index within this subchannel
     * @return int Physical index on parent device
     */
    protected function mapToPhysical(int $logical): int
    {
        if ($logical < 0 || $logical >= count($this->physical_indices)) {
            throw new \OutOfBoundsException(
                "Subchannel pixel index {$logical} out of bounds (0-" . (count($this->physical_indices) - 1) . ")"
            );
        }
        
        return $this->physical_indices[$logical];
    }

    // ========================================
    // Delegate all operations to parent with index translation
    // ========================================

    public function begin(): void
    {
        // Parent already initialized - no-op
    }

    public function show(): static
    {
        $this->parent->show();
        return $this;
    }

    public function setPixelColor(int $n, int $r, int $g, int $b, int $w = 0): void
    {
        $this->parent->setPixelColor($this->mapToPhysical($n), $r, $g, $b, $w);
    }

    public function setPixelColorHex(int $pixel, int $color): void
    {
        $this->parent->setPixelColorHex($this->mapToPhysical($pixel), $color);
    }

    public function fill(int $color, int $first = 0, int $count = 0): static
    {
        if ($count === 0) {
            $count = $this->num_pixels - $first;
        }

        for ($i = $first; $i < $first + $count && $i < $this->num_pixels; $i++) {
            $this->parent->setPixelColorHex($this->mapToPhysical($i), $color);
        }

        return $this;
    }

    public function getPixelColor(int $n): int
    {
        return $this->parent->getPixelColor($this->mapToPhysical($n));
    }

    public function setBrightness(int $b): void
    {
        // Brightness is global on parent - affects all subchannels
        $this->parent->setBrightness($b);
    }

    public function getBrightness(): int
    {
        return $this->parent->getBrightness();
    }

    public function clear(): static
    {
        for ($i = 0; $i < $this->num_pixels; $i++) {
            $this->parent->setPixelColorHex($this->mapToPhysical($i), 0x000000);
        }
        return $this;
    }

    public function getPixelCount(): int
    {
        return $this->num_pixels;
    }

    public function getPixels(): array
    {
        $pixels = [];
        for ($i = 0; $i < $this->num_pixels; $i++) {
            $pixels[$i] = $this->parent->getPixelColor($this->mapToPhysical($i));
        }
        return $pixels;
    }

    // ========================================
    // Advanced methods with index translation
    // ========================================

    public function rotate(int $positions = 1): static
    {
        if ($this->num_pixels <= 1) {
            return $this;
        }

        // Get current pixel values
        $pixels = $this->getPixels();

        // Rotate the array
        $positions = $positions % $this->num_pixels;
        if ($positions < 0) {
            $positions += $this->num_pixels;
        }

        $rotated = array_merge(
            array_slice($pixels, -$positions),
            array_slice($pixels, 0, -$positions)
        );

        // Set rotated values
        for ($i = 0; $i < $this->num_pixels; $i++) {
            $this->parent->setPixelColorHex($this->mapToPhysical($i), $rotated[$i]);
        }

        return $this;
    }

    public function reverse(): static
    {
        $pixels = $this->getPixels();
        $reversed = array_reverse($pixels);

        for ($i = 0; $i < $this->num_pixels; $i++) {
            $this->parent->setPixelColorHex($this->mapToPhysical($i), $reversed[$i]);
        }

        return $this;
    }

    public function fadeIn(int $duration_ms = 1000): static
    {
        $steps = 50;
        $delay = (int)(($duration_ms * 1000) / $steps);

        for ($step = 0; $step <= $steps; $step++) {
            $brightness = (int)(($step / $steps) * 255);
            $this->parent->setBrightness($brightness);
            $this->show();
            usleep($delay);
        }

        return $this;
    }

    public function fadeOut(int $duration_ms = 1000): static
    {
        $steps = 50;
        $delay = (int)(($duration_ms * 1000) / $steps);

        for ($step = $steps; $step >= 0; $step--) {
            $brightness = (int)(($step / $steps) * 255);
            $this->parent->setBrightness($brightness);
            $this->show();
            usleep($delay);
        }

        return $this;
    }

    // Note: These methods don't make sense for subchannels as they affect the whole parent device
    // but we need to implement them to maintain interface compatibility

    public function updateLength(int $n): static
    {
        throw new \RuntimeException(
            "Cannot update length of virtual subchannel. This would affect the parent device."
        );
    }

    public function updateType(NeoPixelType $type): static
    {
        throw new \RuntimeException(
            "Cannot update type of virtual subchannel. This would affect the parent device."
        );
    }

    public function setDevicePath(string $path): static
    {
        throw new \RuntimeException(
            "Cannot update device path of virtual subchannel. This would affect the parent device."
        );
    }

    public function animate(Animation $animation, int $duration_ms = 5000, array $options = []): static
    {
        // For now, animations on subchannels aren't supported
        // Would need to implement subchannel-aware animation system
        throw new \RuntimeException(
            "Animations on virtual subchannels not yet implemented. Use parent device animations instead."
        );
    }
}

