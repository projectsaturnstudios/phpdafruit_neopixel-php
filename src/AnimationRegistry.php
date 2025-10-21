<?php

namespace PhpdaFruit\NeoPixels;

use PhpdaFruit\NeoPixels\Enums\Animation;

/**
 * AnimationRegistry
 * 
 * Central registry for registering all built-in animations
 */
class AnimationRegistry
{
    /**
     * Register all built-in animations
     * 
     * @return int Number of animations registered
     */
    public static function registerAll(): int
    {
        $registrations = [
            // Basic Effects (15 - ALL COMPLETE!)
            Animation::STATIC => \PhpdaFruit\NeoPixels\Animations\Visualizations\StaticVisualization::class,
            Animation::BLINK => \PhpdaFruit\NeoPixels\Animations\Visualizations\BlinkVisualization::class,
            Animation::BREATHE => \PhpdaFruit\NeoPixels\Animations\Visualizations\BreatheVisualization::class,
            Animation::WIPE => \PhpdaFruit\NeoPixels\Animations\Visualizations\WipeVisualization::class,
            Animation::WIPE_RANDOM => \PhpdaFruit\NeoPixels\Animations\Visualizations\WipeRandomVisualization::class,
            Animation::RANDOM_COLORS => \PhpdaFruit\NeoPixels\Animations\Visualizations\RandomColorsVisualization::class,
            Animation::SWEEP => \PhpdaFruit\NeoPixels\Animations\Visualizations\SweepVisualization::class,
            Animation::DYNAMIC => \PhpdaFruit\NeoPixels\Animations\Visualizations\DynamicVisualization::class,
            Animation::COLORLOOP => \PhpdaFruit\NeoPixels\Animations\Visualizations\ColorloopVisualization::class,
            Animation::RAINBOW => \PhpdaFruit\NeoPixels\Animations\Visualizations\RainbowVisualization::class,
            Animation::SCAN => \PhpdaFruit\NeoPixels\Animations\Visualizations\ScanVisualization::class,
            Animation::SCAN_DUAL => \PhpdaFruit\NeoPixels\Animations\Visualizations\ScanDualVisualization::class,
            Animation::FADE => \PhpdaFruit\NeoPixels\Animations\Visualizations\FadeVisualization::class,
            Animation::THEATER_CHASE => \PhpdaFruit\NeoPixels\Animations\Visualizations\TheaterChaseVisualization::class,
            Animation::THEATER_CHASE_RAINBOW => \PhpdaFruit\NeoPixels\Animations\Visualizations\TheaterChaseRainbowVisualization::class,
            
            // Sparkle & Twinkle (3)
            Animation::SPARKLE => \PhpdaFruit\NeoPixels\Animations\Visualizations\SparkleVisualization::class,
            Animation::STROBE => \PhpdaFruit\NeoPixels\Animations\Visualizations\StrobeVisualization::class,
            Animation::BLINK_RAINBOW => \PhpdaFruit\NeoPixels\Animations\Visualizations\BlinkRainbowVisualization::class,
            
            // Comet & Meteor (1)
            Animation::METEOR_SHOWER => \PhpdaFruit\NeoPixels\Animations\Visualizations\MeteorShowerVisualization::class,
        ];

        AnimationFactory::registerBatch($registrations);

        return count($registrations);
    }

    /**
     * Auto-discover and register visualizations from the Visualizations directory
     * 
     * @return int Number of animations registered
     */
    public static function autoDiscover(): int
    {
        $directory = __DIR__ . '/Animations/Visualizations';
        $namespace = 'PhpdaFruit\\NeoPixels\\Animations\\Visualizations';
        
        return AnimationFactory::autoDiscover($directory, $namespace);
    }

    /**
     * Initialize the animation system
     * Uses auto-discovery by default
     * 
     * @param bool $useAutoDiscovery If true, auto-discover; if false, use manual registration
     * @return int Number of animations registered
     */
    public static function initialize(bool $useAutoDiscovery = true): int
    {
        if ($useAutoDiscovery) {
            return static::autoDiscover();
        }
        
        return static::registerAll();
    }
}

