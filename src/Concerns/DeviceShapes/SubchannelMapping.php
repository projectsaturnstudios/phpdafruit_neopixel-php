<?php

namespace PhpdaFruit\NeoPixels\Concerns\DeviceShapes;

use PhpdaFruit\NeoPixels\VirtualSubchannel;

/**
 * SubchannelMapping Trait
 *
 * Splits a single physical device into multiple virtual subchannels
 * Each subchannel acts as an independent PixelChannel with its own
 * logical index space, but all share the same physical hardware
 *
 * Example use cases:
 * - NeoPixel Jewel split into center (1 LED) and ring (6 LEDs)
 * - LED strip divided into zones (top half, bottom half)
 * - Multi-zone devices on a single data line
 *
 * Usage:
 * 1. Add trait to your DeviceShape class
 * 2. Define $subchannel_map array of arrays (each inner array = physical indices)
 * 3. Use getSubchannel(index) to get virtual channel objects
 */
trait SubchannelMapping
{
    /**
     * Subchannel definitions: array of arrays
     * Each inner array contains the physical pixel indices for that subchannel
     *
     * Example: [[0], [1, 2, 3, 4, 5, 6]] means:
     *   Subchannel 0 = physical index 0 (1 pixel)
     *   Subchannel 1 = physical indices 1-6 (6 pixels)
     *
     * @var array<int, array<int>>
     */
    protected array $subchannel_map = [];

    /**
     * Cache of instantiated subchannel objects
     *
     * @var array<int, VirtualSubchannel>
     */
    protected array $subchannel_cache = [];

    /**
     * Get a virtual subchannel by index
     *
     * Returns a VirtualSubchannel object that acts like an independent
     * PixelChannel but operates on a subset of this device's pixels
     *
     * @param int $index Subchannel index (0-based)
     * @return VirtualSubchannel
     * @throws \OutOfBoundsException If subchannel index doesn't exist
     */
    public function getSubchannel(int $index): VirtualSubchannel
    {
        if (!isset($this->subchannel_map[$index])) {
            throw new \OutOfBoundsException(
                "Subchannel {$index} not defined. Available subchannels: 0-" . (count($this->subchannel_map) - 1)
            );
        }

        // Return cached instance if exists
        if (isset($this->subchannel_cache[$index])) {
            return $this->subchannel_cache[$index];
        }

        // Create new VirtualSubchannel
        $physical_indices = $this->subchannel_map[$index];
        $subchannel = new VirtualSubchannel($this, $physical_indices);

        // Cache it
        $this->subchannel_cache[$index] = $subchannel;

        return $subchannel;
    }

    /**
     * Get all subchannels
     *
     * @return array<int, VirtualSubchannel>
     */
    public function getSubchannels(): array
    {
        $subchannels = [];
        for ($i = 0; $i < count($this->subchannel_map); $i++) {
            $subchannels[$i] = $this->getSubchannel($i);
        }
        return $subchannels;
    }

    /**
     * Get the number of defined subchannels
     *
     * @return int
     */
    public function getSubchannelCount(): int
    {
        return count($this->subchannel_map);
    }

    /**
     * Get the subchannel map definition
     *
     * @return array<int, array<int>>
     */
    public function getSubchannelMap(): array
    {
        return $this->subchannel_map;
    }

    /**
     * Set the subchannel map at runtime
     *
     * @param array<int, array<int>> $map Array of arrays defining subchannels
     * @return static
     */
    public function setSubchannelMap(array $map): static
    {
        $this->subchannel_map = $map;
        $this->subchannel_cache = []; // Clear cache
        return $this;
    }

    /**
     * Helper: Create subchannel map by splitting strip into equal zones
     *
     * @param int $total_pixels Total number of pixels
     * @param int $num_zones Number of zones to create
     * @return array<int, array<int>>
     */
    protected static function splitIntoZones(int $total_pixels, int $num_zones): array
    {
        $pixels_per_zone = (int)floor($total_pixels / $num_zones);
        $map = [];

        for ($zone = 0; $zone < $num_zones; $zone++) {
            $start = $zone * $pixels_per_zone;
            $end = ($zone === $num_zones - 1) ? $total_pixels - 1 : ($start + $pixels_per_zone - 1);
            $map[$zone] = range($start, $end);
        }

        return $map;
    }

    /**
     * Helper: Create subchannel map by custom ranges
     *
     * @param array<array{start: int, end: int}> $ranges Array of start/end pairs
     * @return array<int, array<int>>
     */
    protected static function createFromRanges(array $ranges): array
    {
        $map = [];
        foreach ($ranges as $i => $range) {
            $map[$i] = range($range['start'], $range['end']);
        }
        return $map;
    }
}
