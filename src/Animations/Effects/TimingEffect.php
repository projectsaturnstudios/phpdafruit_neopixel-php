<?php

namespace PhpdaFruit\NeoPixels\Animations\Effects;

/**
 * TimingEffect Trait
 * 
 * Provides duration-based animation timing and frame management
 */
trait TimingEffect
{
    /**
     * Run animation loop for specified duration
     * 
     * @param int $duration_ms Duration in milliseconds
     * @param callable $callback Callback to execute each frame (receives: $elapsed_ms, $progress)
     * @param int $frame_delay_us Frame delay in microseconds (default 20ms = ~50fps)
     * @return void
     */
    protected function runForDuration(int $duration_ms, callable $callback, int $frame_delay_us = 20000): void
    {
        $startTime = microtime(true);
        $elapsed = 0;
        
        while ($elapsed < $duration_ms) {
            $progress = min(1.0, $elapsed / $duration_ms);
            
            // Call the animation frame callback
            $callback($elapsed, $progress);
            
            // Frame delay
            usleep($frame_delay_us);
            
            // Update elapsed time
            $elapsed = (microtime(true) - $startTime) * 1000;
        }
    }

    /**
     * Calculate frame count for duration and frame rate
     * 
     * @param int $duration_ms Duration in milliseconds
     * @param int $fps Frames per second
     * @return int Number of frames
     */
    protected function calculateFrames(int $duration_ms, int $fps = 50): int
    {
        return max(1, (int)(($duration_ms / 1000) * $fps));
    }

    /**
     * Calculate frame delay for target FPS
     * 
     * @param int $fps Target frames per second
     * @return int Frame delay in microseconds
     */
    protected function calculateFrameDelay(int $fps): int
    {
        return (int)(1000000 / $fps);
    }

    /**
     * Run animation with fixed frame count
     * 
     * @param int $frames Number of frames to run
     * @param callable $callback Callback to execute each frame (receives: $frame, $progress)
     * @param int $frame_delay_us Frame delay in microseconds
     * @return void
     */
    protected function runForFrames(int $frames, callable $callback, int $frame_delay_us = 20000): void
    {
        for ($frame = 0; $frame < $frames; $frame++) {
            $progress = $frame / max(1, $frames - 1);
            
            // Call the animation frame callback
            $callback($frame, $progress);
            
            // Frame delay
            if ($frame < $frames - 1) {
                usleep($frame_delay_us);
            }
        }
    }

    /**
     * Calculate iterations for duration and frame delay
     * 
     * @param int $duration_ms Duration in milliseconds
     * @param int $frame_delay_ms Frame delay in milliseconds
     * @return int Number of iterations
     */
    protected function calculateIterations(int $duration_ms, int $frame_delay_ms): int
    {
        return max(1, (int)($duration_ms / $frame_delay_ms));
    }

    /**
     * Get a timer object for manual elapsed time tracking
     * 
     * @return object Timer object with getElapsed() and getProgress() methods
     */
    protected function createTimer(int $duration_ms): object
    {
        $startTime = microtime(true);
        
        return new class($startTime, $duration_ms) {
            public function __construct(
                private float $startTime,
                private int $duration_ms
            ) {}
            
            public function getElapsed(): float
            {
                return (microtime(true) - $this->startTime) * 1000;
            }
            
            public function getProgress(): float
            {
                return min(1.0, $this->getElapsed() / $this->duration_ms);
            }
            
            public function isComplete(): bool
            {
                return $this->getElapsed() >= $this->duration_ms;
            }
            
            public function reset(): void
            {
                $this->startTime = microtime(true);
            }
        };
    }

    /**
     * Wait for specified duration in milliseconds
     * 
     * @param int $ms Milliseconds to wait
     * @return void
     */
    protected function wait(int $ms): void
    {
        usleep($ms * 1000);
    }
}

