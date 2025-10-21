<?php

namespace PhpdaFruit\NeoPixels;

use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\Contracts\Animations\Visualizations\AnimationVisualization;
use PhpdaFruit\NeoPixels\Exceptions\AnimationNotFoundException;

/**
 * AnimationFactory
 * 
 * Factory for creating and managing animation visualizations
 * Maps Animation enum cases to concrete visualization classes
 */
class AnimationFactory
{
    /**
     * Registry mapping Animation enum to visualization class names
     * 
     * @var array<string, class-string<AnimationVisualization>>
     */
    protected static array $registry = [];

    /**
     * Cache of instantiated visualizations
     * 
     * @var array<string, AnimationVisualization>
     */
    protected static array $instances = [];

    /**
     * Register an animation visualization
     * 
     * @param Animation $animation
     * @param class-string<AnimationVisualization> $visualizationClass
     * @return void
     */
    public static function register(Animation $animation, string $visualizationClass): void
    {
        if (!is_subclass_of($visualizationClass, AnimationVisualization::class)) {
            throw new \InvalidArgumentException(
                "Visualization class must implement AnimationVisualization interface"
            );
        }

        static::$registry[$animation->value] = $visualizationClass;
    }

    /**
     * Register multiple animations at once
     * 
     * @param array<Animation, class-string<AnimationVisualization>> $mappings
     * @return void
     */
    public static function registerBatch(array $mappings): void
    {
        foreach ($mappings as $animation => $visualizationClass) {
            static::register($animation, $visualizationClass);
        }
    }

    /**
     * Create or retrieve a visualization for the given animation
     * 
     * @param Animation $animation
     * @return AnimationVisualization
     * @throws AnimationNotFoundException
     */
    public static function create(Animation $animation): AnimationVisualization
    {
        // Return cached instance if exists
        if (isset(static::$instances[$animation->value])) {
            return static::$instances[$animation->value];
        }

        // Check if animation is registered
        if (!isset(static::$registry[$animation->value])) {
            throw new AnimationNotFoundException(
                "No visualization registered for animation: {$animation->getName()}"
            );
        }

        // Instantiate and cache
        $visualizationClass = static::$registry[$animation->value];
        $instance = new $visualizationClass();

        static::$instances[$animation->value] = $instance;

        return $instance;
    }

    /**
     * Check if an animation has a registered visualization
     * 
     * @param Animation $animation
     * @return bool
     */
    public static function has(Animation $animation): bool
    {
        return isset(static::$registry[$animation->value]);
    }

    /**
     * Get all registered animations
     * 
     * @return array<Animation>
     */
    public static function getRegistered(): array
    {
        return array_map(
            fn($value) => Animation::from($value),
            array_keys(static::$registry)
        );
    }

    /**
     * Clear all registrations and cached instances
     * 
     * @return void
     */
    public static function clear(): void
    {
        static::$registry = [];
        static::$instances = [];
    }

    /**
     * Auto-discover and register all visualizations in a directory
     * 
     * @param string $directory
     * @param string $namespace
     * @return int Number of registered animations
     */
    public static function autoDiscover(string $directory, string $namespace): int
    {
        $count = 0;

        if (!is_dir($directory)) {
            return $count;
        }

        $files = glob($directory . '/*.php');

        foreach ($files as $file) {
            $className = $namespace . '\\' . basename($file, '.php');

            if (!class_exists($className)) {
                continue;
            }

            if (!is_subclass_of($className, AnimationVisualization::class)) {
                continue;
            }

            try {
                $instance = new $className();
                $animation = $instance->getAnimationType();
                static::register($animation, $className);
                $count++;
            } catch (\Throwable $e) {
                // Skip classes that can't be instantiated or don't have proper animation type
                continue;
            }
        }

        return $count;
    }
}

