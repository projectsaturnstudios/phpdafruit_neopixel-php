<?php

namespace PhpdaFruit\NeoPixels\DeviceShapes;

use PhpdaFruit\NeoPixels\Concerns\DeviceShapes\SubchannelMapping;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use Phpdafruit\NeoPixels\PixelChannel;

/**
 * SplitJewel - Adafruit NeoPixel Jewel split into virtual subchannels
 *
 * Physical wiring: Center LED at index 0, ring LEDs at 1-6 (clockwise from top)
 *
 * Virtual subchannels:
 * - Subchannel 0: Center LED (1 pixel) - physical index 0
 * - Subchannel 1: Ring LEDs (6 pixels) - physical indices 1-6
 *
 * Usage:
 * $jewel = new SplitJewel();
 * $center = $jewel->getSubchannel(0);  // or getCenter()
 * $ring = $jewel->getSubchannel(1);    // or getRing()
 *
 * $center->fill(0xFF0000)->show();  // Red center
 * $ring->fill(0x00FF00)->show();    // Green ring
 */
class Lollipop extends PixelChannel
{
    use SubchannelMapping;

    public function __construct(
        protected string $path = '/dev/spidev0.0',
        NeoPixelType $type = NeoPixelType::RGB
    ) {
        parent::__construct(7, $path, $type);

        /**
         * Subchannel map:
         * Subchannel 0 = Center LED (physical index 0)
         * Subchannel 1 = Ring LEDs (physical indices 1-6)
         */
        $this->subchannel_map = [
            //jewel
            [0, 1, 3, 5],
            [ 2, 4, 6],
            // stick
            [ 7, 9, 11],
            [ 8, 10, 12],
        ];
    }

    /**
     * Get the center LED subchannel
     *
     * @return \PhpdaFruit\NeoPixels\VirtualSubchannel
     */
    public function getCenter(): \PhpdaFruit\NeoPixels\VirtualSubchannel
    {
        return $this->getSubchannel(0);
    }

    /**
     * Get the ring LEDs subchannel
     *
     * @return \PhpdaFruit\NeoPixels\VirtualSubchannel
     */
    public function getRing(): \PhpdaFruit\NeoPixels\VirtualSubchannel
    {
        return $this->getSubchannel(1);
    }

}
