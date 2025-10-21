<?php

namespace PhpdaFruit\NeoPixels;

use Exception;

class PixelBus
{
    /** @var array<PixelChannel  */
    protected array $pixel_sources = [];

    public function __construct(
        array|PixelChannel|null $channels = null
    ) {
        if (is_array($channels)) {
            foreach ($channels as $name => $channel) {
                $this->addPixelChannel($name, $channel);
            }
        } elseif ($channels instanceof PixelChannel) {
            $this->addPixelChannel('default', $channels);
        }
    }

    public function addPixelChannel(string $name, PixelChannel $channel): static
    {
        $this->pixel_sources[$name] = $channel;
        return $this;
    }

    /**
     * @param string $channel_name
     * @param string $method
     * @param array $args
     * @return void
     * @throws Exception
     */
    public function useSource(string $channel_name, string $method, array $args): void
    {
        if (!isset($this->pixel_sources[$channel_name])) throw new Exception("Pixel channel {$channel_name} not found");

        $this->pixel_sources[$channel_name]->$method(...$args);
    }

    /**
     * Broadcast a method call to all channels
     *
     * @param string $method
     * @param array $args
     * @return void
     */
    public function broadcast(string $method, array $args = []): void
    {
        foreach ($this->pixel_sources as $channel) {
            $channel->$method(...$args);
        }
    }

    /**
     * Fill all channels with the same color
     *
     * @param int $color
     * @return static
     */
    public function fillAll(int $color): static
    {
        foreach ($this->pixel_sources as $channel) {
            $channel->fill($color);
        }
        return $this;
    }

    /**
     * Clear all channels
     *
     * @return static
     */
    public function clearAll(): static
    {
        foreach ($this->pixel_sources as $channel) {
            $channel->clear();
        }
        return $this;
    }

    /**
     * Show (update) all channels
     *
     * @return static
     */
    public function showAll(): static
    {
        foreach ($this->pixel_sources as $channel) {
            $channel->show();
        }
        return $this;
    }

    /**
     * Set brightness on all channels
     *
     * @param int $brightness
     * @return static
     */
    public function setBrightnessAll(int $brightness): static
    {
        foreach ($this->pixel_sources as $channel) {
            $channel->setBrightness($brightness);
        }
        return $this;
    }

    /**
     * Get a specific channel by name
     *
     * @param string $name
     * @return PixelChannel|null
     */
    public function getChannel(string $name): ?PixelChannel
    {
        return $this->pixel_sources[$name] ?? null;
    }

    /**
     * Check if a channel exists
     *
     * @param string $name
     * @return bool
     */
    public function hasChannel(string $name): bool
    {
        return isset($this->pixel_sources[$name]);
    }

    /**
     * Get all channel names
     *
     * @return array
     */
    public function getChannelNames(): array
    {
        return array_keys($this->pixel_sources);
    }

    /**
     * Remove a channel
     *
     * @param string $name
     * @return static
     */
    public function removeChannel(string $name): static
    {
        unset($this->pixel_sources[$name]);
        return $this;
    }
}