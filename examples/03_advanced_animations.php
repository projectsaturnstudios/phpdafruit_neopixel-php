#!/usr/bin/env php
<?php
/**
 * Example 3: Advanced Multi-Device Animations
 * 
 * This example showcases complex, synchronized animations across multiple
 * LED devices demonstrating the full power of PixelBus.
 * 
 * Hardware Setup:
 * - LED Strip: 30 pixels on /dev/spidev0.0 (SPI0) - RGB order
 * - RGB Fan: 4 pixels on /dev/spidev1.0 (SPI1) - GRB order
 * 
 * Features Demonstrated:
 * - Rainbow wave animation
 * - Bidirectional chase effects
 * - Color matching between devices
 * - Pulse/strobe effects
 * - Theater chase with color rotation
 * - Complementary color display
 * - Fire effect simulation
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpdaFruit\NeoPixels\PixelBus;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        🎨 ADVANCED MULTI-DEVICE ANIMATIONS 🎨             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Helper functions for color manipulation
function wheel(int $pos): int {
    $pos = $pos % 256;
    if ($pos < 85) {
        return (($pos * 3) << 16) | ((255 - $pos * 3) << 8);
    } elseif ($pos < 170) {
        $pos -= 85;
        return ((255 - $pos * 3) << 16) | (($pos * 3) << 0);
    } else {
        $pos -= 170;
        return (($pos * 3) << 8) | (255 - $pos * 3);
    }
}

function dim_color(int $color, float $factor): int {
    $r = (($color >> 16) & 0xFF) * $factor;
    $g = (($color >> 8) & 0xFF) * $factor;
    $b = ($color & 0xFF) * $factor;
    return ((int)$r << 16) | ((int)$g << 8) | (int)$b;
}

function lerp_color(int $color1, int $color2, float $t): int {
    $r1 = ($color1 >> 16) & 0xFF;
    $g1 = ($color1 >> 8) & 0xFF;
    $b1 = $color1 & 0xFF;
    
    $r2 = ($color2 >> 16) & 0xFF;
    $g2 = ($color2 >> 8) & 0xFF;
    $b2 = $color2 & 0xFF;
    
    $r = (int)($r1 + ($r2 - $r1) * $t);
    $g = (int)($g1 + ($g2 - $g1) * $t);
    $b = (int)($b1 + ($b2 - $b1) * $t);
    
    return ($r << 16) | ($g << 8) | $b;
}

// Initialize devices
echo "🔧 Initializing devices...\n";
$stripChannel = new PixelChannel(30, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
$fanChannel = new PixelChannel(4, SPIDevice::SPI_1_0->value, NeoPixelType::GRB);

$bus = new PixelBus([
    'strip' => $stripChannel,
    'fan' => $fanChannel,
]);

echo "  ✓ LED Strip (30 pixels) on SPI0\n";
echo "  ✓ RGB Fan (4 pixels) on SPI1\n\n";

// ═══════════════════════════════════════════════════════════════
// Animation 1: Rainbow Wave with Fan Color Match
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 1: Rainbow Wave with Fan Color Match\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

for ($cycle = 0; $cycle < 80; $cycle++) {
    // Create rainbow on strip
    for ($i = 0; $i < 30; $i++) {
        $color = wheel((($i * 256 / 30) + $cycle * 3) & 255);
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;
        $stripChannel->setPixelColor($i, $r, $g, $b);
    }
    
    // Fan matches the middle of the strip
    $fanColor = $stripChannel->getPixelColor(15);
    $fanChannel->fill($fanColor);
    
    $stripChannel->show();
    $fanChannel->show();
    usleep(30000);
}

$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Animation 2: Bidirectional Chase
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 2: Bidirectional Chase with Energy Transfer\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

for ($run = 0; $run < 3; $run++) {
    // Chase from start to end (Red)
    for ($i = 0; $i < 30; $i++) {
        $stripChannel->clear();
        $stripChannel->setPixelColor($i, 255, 0, 0);
        if ($i > 0) $stripChannel->setPixelColor($i - 1, 100, 0, 0); // Trail
        if ($i > 1) $stripChannel->setPixelColor($i - 2, 30, 0, 0);
        $stripChannel->show();
        usleep(30000);
    }
    
    // Transfer to fan
    for ($blink = 0; $blink < 2; $blink++) {
        $fanChannel->fill(0xFF0000);
        $fanChannel->show();
        usleep(100000);
        $fanChannel->clear();
        $fanChannel->show();
        usleep(100000);
    }
    
    // Chase from end to start (Blue)
    for ($i = 29; $i >= 0; $i--) {
        $stripChannel->clear();
        $stripChannel->setPixelColor($i, 0, 0, 255);
        if ($i < 29) $stripChannel->setPixelColor($i + 1, 0, 0, 100); // Trail
        if ($i < 28) $stripChannel->setPixelColor($i + 2, 0, 0, 30);
        $stripChannel->show();
        usleep(30000);
    }
}

$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Animation 3: Theater Chase with Color Rotation
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 3: Theater Chase with Color Rotation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$colors = [0xFF0000, 0x00FF00, 0x0000FF, 0xFFFF00, 0xFF00FF, 0x00FFFF];

for ($cycle = 0; $cycle < 40; $cycle++) {
    $color = $colors[$cycle % count($colors)];
    
    for ($pattern = 0; $pattern < 3; $pattern++) {
        $stripChannel->clear();
        
        for ($i = $pattern; $i < 30; $i += 3) {
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;
            $stripChannel->setPixelColor($i, $r, $g, $b);
        }
        
        // Fan shows current color
        $fanChannel->fill($color);
        
        $stripChannel->show();
        $fanChannel->show();
        usleep(80000);
    }
}

$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Animation 4: Breathing with Color Transition
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 4: Breathing with Color Transition\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$colorStops = [0xFF0000, 0xFFFF00, 0x00FF00, 0x00FFFF, 0x0000FF, 0xFF00FF];

foreach ($colorStops as $targetColor) {
    $stripChannel->fill($targetColor);
    $fanChannel->fill($targetColor);
    
    // Breathe in
    for ($brightness = 0; $brightness <= 255; $brightness += 8) {
        $stripChannel->setBrightness($brightness);
        $fanChannel->setBrightness($brightness);
        $stripChannel->show();
        $fanChannel->show();
        usleep(15000);
    }
    
    // Breathe out
    for ($brightness = 255; $brightness >= 0; $brightness -= 8) {
        $stripChannel->setBrightness($brightness);
        $fanChannel->setBrightness($brightness);
        $stripChannel->show();
        $fanChannel->show();
        usleep(15000);
    }
}

$stripChannel->setBrightness(255);
$fanChannel->setBrightness(255);
$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Animation 5: Fire Effect Simulation
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 5: Fire Effect Simulation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

for ($frame = 0; $frame < 150; $frame++) {
    // Create flickering fire colors
    for ($i = 0; $i < 30; $i++) {
        // Random intensity for each pixel
        $heat = rand(80, 255);
        $r = $heat;
        $g = (int)($heat * 0.4);
        $b = 0;
        
        $stripChannel->setPixelColor($i, $r, $g, $b);
    }
    
    // Fan shows average fire color with flicker
    $fanHeat = rand(120, 255);
    $fr = $fanHeat;
    $fg = (int)($fanHeat * 0.4);
    $fanChannel->fill(($fr << 16) | ($fg << 8));
    
    $stripChannel->show();
    $fanChannel->show();
    usleep(50000);
}

$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Animation 6: Complementary Color Display
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 6: Complementary Color Display\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$complementaryPairs = [
    [0xFF0000, 0x00FFFF], // Red <-> Cyan
    [0x00FF00, 0xFF00FF], // Green <-> Magenta
    [0x0000FF, 0xFFFF00], // Blue <-> Yellow
    [0xFF8000, 0x007FFF], // Orange <-> Sky Blue
];

foreach ($complementaryPairs as [$color1, $color2]) {
    // Strip shows one color
    $stripChannel->fill($color1);
    
    // Fan shows complementary color
    $fanChannel->fill($color2);
    
    $stripChannel->show();
    $fanChannel->show();
    sleep(1);
    
    // Swap colors
    $stripChannel->fill($color2);
    $fanChannel->fill($color1);
    
    $stripChannel->show();
    $fanChannel->show();
    sleep(1);
}

$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Animation 7: Loading Bar Simulation
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 7: Loading Bar with Progress Indicator\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

for ($progress = 0; $progress <= 30; $progress++) {
    $percentage = (int)(($progress / 30) * 100);
    echo "\r  📊 Progress: {$percentage}%  ";
    
    // Fill strip progressively
    $stripChannel->clear();
    for ($i = 0; $i < $progress; $i++) {
        // Gradient from red (start) to green (end)
        $color = lerp_color(0xFF0000, 0x00FF00, $i / 30);
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;
        $stripChannel->setPixelColor($i, $r, $g, $b);
    }
    
    // Fan brightness represents progress
    $fanBrightness = (int)(($progress / 30) * 255);
    $fanChannel->setBrightness($fanBrightness);
    $fanChannel->fill(0x00FF00);
    
    $stripChannel->show();
    $fanChannel->show();
    usleep(100000);
}

echo "\n  ✓ Complete!\n";
sleep(1);

$fanChannel->setBrightness(255);
$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Animation 8: Pulse/Strobe Effect
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Animation 8: Synchronized Pulse/Strobe\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$strobeColors = [0xFF0000, 0x00FF00, 0x0000FF, 0xFFFFFF];

foreach ($strobeColors as $color) {
    for ($pulse = 0; $pulse < 5; $pulse++) {
        $stripChannel->fill($color);
        $fanChannel->fill($color);
        $stripChannel->show();
        $fanChannel->show();
        usleep(100000);
        
        $stripChannel->clear();
        $fanChannel->clear();
        $stripChannel->show();
        $fanChannel->show();
        usleep(100000);
    }
    usleep(200000);
}

echo "\n";

// ═══════════════════════════════════════════════════════════════
// Finale: Rainbow Explosion
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎆 Finale: Rainbow Explosion\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Quick rainbow flash
for ($i = 0; $i < 256; $i += 4) {
    $color = wheel($i);
    $stripChannel->fill($color);
    $fanChannel->fill($color);
    $stripChannel->show();
    $fanChannel->show();
    usleep(10000);
}

// White flash
$stripChannel->fill(0xFFFFFF);
$fanChannel->fill(0xFFFFFF);
$stripChannel->show();
$fanChannel->show();
usleep(500000);

// Fade out
for ($brightness = 255; $brightness >= 0; $brightness -= 5) {
    $stripChannel->setBrightness($brightness);
    $fanChannel->setBrightness($brightness);
    $stripChannel->show();
    $fanChannel->show();
    usleep(20000);
}

$stripChannel->clear();
$fanChannel->clear();
$stripChannel->show();
$fanChannel->show();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                  ✨ ALL ANIMATIONS COMPLETE ✨             ║\n";
echo "║                                                            ║\n";
echo "║  Animations Demonstrated:                                  ║\n";
echo "║   ✓ Rainbow wave with color matching                      ║\n";
echo "║   ✓ Bidirectional chase with energy transfer              ║\n";
echo "║   ✓ Theater chase with color rotation                     ║\n";
echo "║   ✓ Breathing with color transitions                      ║\n";
echo "║   ✓ Fire effect simulation                                ║\n";
echo "║   ✓ Complementary color display                           ║\n";
echo "║   ✓ Loading bar with progress indicator                   ║\n";
echo "║   ✓ Synchronized pulse/strobe                             ║\n";
echo "║   ✓ Rainbow explosion finale                              ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

