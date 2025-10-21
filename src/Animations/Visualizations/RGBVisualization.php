<?php

namespace PhpdaFruit\NeoPixels\Animations\Visualizations;

use PhpdaFruit\NeoPixels\Contracts\Animations\Visualizations\AnimationVisualization as AnimationVisualizationContract;
use PhpdaFruit\NeoPixels\PixelChannel;

/**
 * Abstract RGBVisualization Base Class
 * 
 * Provides common functionality for full animation visualizations
 */
abstract class RGBVisualization implements AnimationVisualizationContract
{
    /**
     * Get the animation name (derived from class name by default)
     */
    public function getName(): string
    {
        $className = class_basename(static::class);
        return str_replace('Visualization', '', $className);
    }

    /**
     * Get default options (can be overridden by child classes)
     */
    public function getDefaultOptions(): array
    {
        return [];
    }

    /**
     * Check if animation is compatible (default: all channels compatible)
     * Override for specific channel type requirements
     */
    public function isCompatible(PixelChannel $channel): bool
    {
        return true;
    }
}
