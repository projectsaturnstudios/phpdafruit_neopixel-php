#!/usr/bin/env php
<?php
/**
 * Example 1: Basic Single PixelChannel Usage
 * 
 * This example demonstrates the fundamentals of using a single PixelChannel
 * to control a NeoPixel LED strip.
 * 
 * Hardware Setup:
 * - 15 RGB LEDs connected to /dev/spidev0.0 (SPI0)
 * - Jetson Orin Nano or Raspberry Pi 5
 * 
 * Features Demonstrated:
 * - Creating a PixelChannel instance
 * - Setting individual pixel colors
 * - Filling all pixels with a color
 * - Clearing the strip
 * - Brightness control
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          🌈 SINGLE CHANNEL BASIC EXAMPLE 🌈               ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Initialize a PixelChannel with 15 RGB LEDs
echo "🔧 Initializing PixelChannel...\n";
$strip = new PixelChannel(
    num_pixels: 15,
    device_path: SPIDevice::SPI_0_0->value,
    neopixel_type: NeoPixelType::RGB
);

echo "  ✓ 15 RGB pixels ready on SPI0\n\n";

// ═══════════════════════════════════════════════════════════════
// Demo 1: Individual Pixel Control
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 1: Setting Individual Pixel Colors\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  🎨 Setting pixels 0-2 to Red, Green, Blue...\n";
$strip->setPixelColor(0, 255, 0, 0);   // Red
$strip->setPixelColor(1, 0, 255, 0);   // Green
$strip->setPixelColor(2, 0, 0, 255);   // Blue
$strip->show();
sleep(2);

$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 2: Fill All Pixels
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 2: Filling All Pixels\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$colors = [
    ['name' => 'Red',     'hex' => 0xFF0000],
    ['name' => 'Green',   'hex' => 0x00FF00],
    ['name' => 'Blue',    'hex' => 0x0000FF],
    ['name' => 'Yellow',  'hex' => 0xFFFF00],
    ['name' => 'Cyan',    'hex' => 0x00FFFF],
    ['name' => 'Magenta', 'hex' => 0xFF00FF],
];

foreach ($colors as $color) {
    echo "  🌈 {$color['name']}...\n";
    $strip->fill($color['hex']);
    $strip->show();
    usleep(500000); // 0.5s
}

$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 3: Partial Fill
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 3: Filling Specific Ranges\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  📊 First 5 pixels: Red\n";
$strip->fill(0xFF0000, 0, 5);
$strip->show();
sleep(1);

echo "  📊 Middle 5 pixels: Green\n";
$strip->fill(0x00FF00, 5, 5);
$strip->show();
sleep(1);

echo "  📊 Last 5 pixels: Blue\n";
$strip->fill(0x0000FF, 10, 5);
$strip->show();
sleep(2);

$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 4: Brightness Control
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 4: Brightness Control\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$strip->fill(0xFFFFFF); // White

echo "  💡 Full brightness (255)...\n";
$strip->setBrightness(255);
$strip->show();
sleep(1);

echo "  💡 Half brightness (128)...\n";
$strip->setBrightness(128);
$strip->show();
sleep(1);

echo "  💡 Low brightness (32)...\n";
$strip->setBrightness(32);
$strip->show();
sleep(1);

$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 5: Breathing Effect
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 5: Breathing Effect\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$strip->fill(0x0000FF); // Blue

echo "  💨 Breathing in...\n";
for ($brightness = 0; $brightness <= 255; $brightness += 5) {
    $strip->setBrightness($brightness);
    $strip->show();
    usleep(20000);
}

echo "  💨 Breathing out...\n";
for ($brightness = 255; $brightness >= 0; $brightness -= 5) {
    $strip->setBrightness($brightness);
    $strip->show();
    usleep(20000);
}

$strip->setBrightness(255);
$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 6: Chase Effect
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 6: Chase Effect\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

for ($run = 0; $run < 2; $run++) {
    echo "  ⚡ Run " . ($run + 1) . "/2...\n";
    
    for ($i = 0; $i < 15; $i++) {
        $strip->clear();
        $strip->setPixelColor($i, 255, 100, 0); // Orange
        $strip->show();
        usleep(50000);
    }
}

$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 7: Reading Pixel Colors
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 7: Reading Pixel Colors\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Set some colors
$strip->setPixelColor(0, 255, 0, 0);   // Red
$strip->setPixelColor(1, 0, 255, 0);   // Green
$strip->setPixelColor(2, 0, 0, 255);   // Blue
$strip->show();

// Read them back
echo "  📖 Reading pixel colors:\n";
echo "     Pixel 0: 0x" . dechex($strip->getPixelColor(0)) . " (should be FF0000 - Red)\n";
echo "     Pixel 1: 0x" . dechex($strip->getPixelColor(1)) . " (should be 00FF00 - Green)\n";
echo "     Pixel 2: 0x" . dechex($strip->getPixelColor(2)) . " (should be 0000FF - Blue)\n";
sleep(2);

$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Finale
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎬 Demo Complete!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                  ✨ ALL DEMOS COMPLETE ✨                  ║\n";
echo "║                                                            ║\n";
echo "║  Features Demonstrated:                                    ║\n";
echo "║   ✓ Individual pixel control                              ║\n";
echo "║   ✓ Fill operations (full & partial)                      ║\n";
echo "║   ✓ Brightness control                                    ║\n";
echo "║   ✓ Breathing effect                                      ║\n";
echo "║   ✓ Chase animation                                       ║\n";
echo "║   ✓ Reading pixel colors                                  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

