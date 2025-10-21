<?php

namespace PhpdaFruit\NeoPixels\DeviceShapes;

use Phpdafruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

/**
 * SingleDiode - Perfect for status indicators, single LED notifications
 */
class SingleDiode extends PixelChannel
{
    public function __construct(
        protected string $path = '/dev/spidev0.0',
        NeoPixelType $type = NeoPixelType::RGB
    ) {
        parent::__construct(1, $path, $type);
    }

    /**
     * Set the LED color directly
     *
     * @param int $color Hex color
     * @return static
     */
    public function setColor(int $color): static
    {
        $this->fill($color);
        return $this;
    }

    /**
     * Turn on to a specific color and show
     *
     * @param int $color Hex color
     * @return static
     */
    public function on(int $color = 0xFFFFFF): static
    {
        $this->fill($color)->show();
        return $this;
    }

    /**
     * Turn off the LED
     *
     * @return static
     */
    public function off(): static
    {
        $this->clear()->show();
        return $this;
    }

    /**
     * Blink the LED
     *
     * @param int $color Hex color
     * @param int $times Number of blinks
     * @param int $on_ms Milliseconds on
     * @param int $off_ms Milliseconds off
     * @return static
     */
    public function blink(int $color = 0xFFFFFF, int $times = 3, int $on_ms = 200, int $off_ms = 200): static
    {
        for ($i = 0; $i < $times; $i++) {
            $this->on($color);
            usleep($on_ms * 1000);
            $this->off();
            if ($i < $times - 1) {
                usleep($off_ms * 1000);
            }
        }
        return $this;
    }

    /**
     * Pulse (breathing) effect
     *
     * @param int $color Hex color
     * @param int $cycles Number of breath cycles
     * @param int $duration_ms Duration per cycle
     * @return static
     */
    public function pulse(int $color = 0xFFFFFF, int $cycles = 1, int $duration_ms = 2000): static
    {
        $this->fill($color);
        
        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            // Breathe in
            $this->fadeIn((int)($duration_ms / 2));
            // Breathe out
            $this->fadeOut((int)($duration_ms / 2));
        }
        
        return $this;
    }

    /**
     * Fast strobe effect
     *
     * @param int $color Hex color
     * @param int $times Number of strobes
     * @param int $delay_ms Delay between strobes
     * @return static
     */
    public function strobe(int $color = 0xFFFFFF, int $times = 10, int $delay_ms = 50): static
    {
        for ($i = 0; $i < $times; $i++) {
            $this->on($color);
            usleep($delay_ms * 1000);
            $this->off();
            usleep($delay_ms * 1000);
        }
        return $this;
    }

    /**
     * Quick status flash
     *
     * @param string $status 'success', 'error', 'warning', 'info'
     * @return static
     */
    public function status(string $status): static
    {
        $colors = [
            'success' => 0x00FF00,
            'error' => 0xFF0000,
            'warning' => 0xFFFF00,
            'info' => 0x0000FF,
        ];
        
        $color = $colors[$status] ?? 0xFFFFFF;
        return $this->blink($color, 2, 150, 150);
    }
}