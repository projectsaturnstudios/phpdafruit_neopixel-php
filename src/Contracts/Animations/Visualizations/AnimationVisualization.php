<?php

namespace PhpdaFruit\NeoPixels\Contracts\Animations\Visualizations;

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * AnimationVisualization Interface
 * 
 * Defines complete animation sequences that can be run on LED channels.
 * Visualizations compose multiple effects and handle timing/sequencing.
 */
interface AnimationVisualization
{
    /**
     * Run the animation on a channel
     * 
     * @param PixelChannel $channel The LED channel to animate
     * @param int $duration_ms Duration in milliseconds
     * @param array $options Animation-specific options
     * @return void
     */
    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void;

    /**
     * Get the animation type this visualization implements
     * 
     * @return Animation
     */
    public function getAnimationType(): Animation;

    /**
     * Get the animation name
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Get default options for this animation
     * 
     * @return array
     */
    public function getDefaultOptions(): array;

    /**
     * Check if this animation is compatible with the given channel
     * 
     * @param PixelChannel $channel
     * @return bool
     */
    public function isCompatible(PixelChannel $channel): bool;
}
