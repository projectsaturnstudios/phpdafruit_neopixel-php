<?php

namespace PhpdaFruit\NeoPixels\Animations\Effects;

/**
 * FadeEffect Trait
 * 
 * Provides color brightness manipulation for fading/dimming effects
 */
trait FadeEffect
{
    /**
     * Dim a color by a brightness factor
     * 
     * @param int $color Color in 0xRRGGBB format
     * @param float $factor Brightness factor (0.0 = black, 1.0 = full brightness)
     * @return int Dimmed color
     */
    protected function dimColor(int $color, float $factor): int
    {
        $factor = max(0.0, min(1.0, $factor)); // Clamp between 0-1
        
        $r = (int)((($color >> 16) & 0xFF) * $factor);
        $g = (int)((($color >> 8) & 0xFF) * $factor);
        $b = (int)(($color & 0xFF) * $factor);
        
        return ($r << 16) | ($g << 8) | $b;
    }

    /**
     * Brighten a color by a factor
     * 
     * @param int $color Color in 0xRRGGBB format
     * @param float $factor Brightness increase factor (1.0 = no change, 2.0 = double brightness)
     * @return int Brightened color (clamped to 0xFF per channel)
     */
    protected function brightenColor(int $color, float $factor): int
    {
        $r = min(255, (int)((($color >> 16) & 0xFF) * $factor));
        $g = min(255, (int)((($color >> 8) & 0xFF) * $factor));
        $b = min(255, (int)(($color & 0xFF) * $factor));
        
        return ($r << 16) | ($g << 8) | $b;
    }

    /**
     * Fade between current color and target color
     * 
     * @param int $fromColor Starting color
     * @param int $toColor Target color
     * @param float $progress Progress (0.0 = fromColor, 1.0 = toColor)
     * @return int Interpolated color
     */
    protected function fadeToColor(int $fromColor, int $toColor, float $progress): int
    {
        $progress = max(0.0, min(1.0, $progress));
        
        $r1 = ($fromColor >> 16) & 0xFF;
        $g1 = ($fromColor >> 8) & 0xFF;
        $b1 = $fromColor & 0xFF;
        
        $r2 = ($toColor >> 16) & 0xFF;
        $g2 = ($toColor >> 8) & 0xFF;
        $b2 = $toColor & 0xFF;
        
        $r = (int)($r1 + ($r2 - $r1) * $progress);
        $g = (int)($g1 + ($g2 - $g1) * $progress);
        $b = (int)($b1 + ($b2 - $b1) * $progress);
        
        return ($r << 16) | ($g << 8) | $b;
    }

    /**
     * Apply a fade curve (ease in/out)
     * 
     * @param float $progress Linear progress (0.0 to 1.0)
     * @param string $curve Curve type: 'linear', 'ease_in', 'ease_out', 'ease_in_out'
     * @return float Curved progress
     */
    protected function fadeCurve(float $progress, string $curve = 'linear'): float
    {
        $progress = max(0.0, min(1.0, $progress));
        
        return match($curve) {
            'ease_in' => $progress * $progress,
            'ease_out' => $progress * (2.0 - $progress),
            'ease_in_out' => $progress < 0.5 
                ? 2.0 * $progress * $progress 
                : -1.0 + (4.0 - 2.0 * $progress) * $progress,
            default => $progress
        };
    }
}

