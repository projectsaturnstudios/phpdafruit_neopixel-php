#!/usr/bin/env php
<?php
/**
 * Example 2: PixelBus with Multiple Channels
 * 
 * This example demonstrates how to use PixelBus to manage multiple
 * independent LED devices simultaneously.
 * 
 * Hardware Setup:
 * - RGB Strip: 15 pixels on /dev/spidev0.0 (SPI0) - RGB order
 * - RGB Fan: 2 pixels on /dev/spidev1.0 (SPI1) - GRB order
 * - Status LED: 1 pixel on /dev/spidev2.0 (SPI2) - RGB order
 * 
 * Features Demonstrated:
 * - Creating and managing multiple PixelChannels via PixelBus
 * - Independent control of each channel
 * - Synchronized operations across channels
 * - Different pixel types on different channels
 * - Adding channels after initialization
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpdaFruit\NeoPixels\PixelBus;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          🚌 PIXELBUS MULTI-CHANNEL EXAMPLE 🚌             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Initialize PixelBus with Multiple Channels
// ═══════════════════════════════════════════════════════════════
echo "🔧 Initializing PixelBus with multiple channels...\n";

$bus = new PixelBus([
    'strip' => new PixelChannel(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
    'fan' => new PixelChannel(2, SPIDevice::SPI_1_0->value, NeoPixelType::GRB),
]);

echo "  ✓ RGB Strip (15 pixels) on SPI0\n";
echo "  ✓ RGB Fan (2 pixels) on SPI1\n";

// Add another channel after initialization
echo "\n  ➕ Adding status LED dynamically...\n";
$bus->addPixelChannel(
    'status',
    new PixelChannel(1, SPIDevice::SPI_2_0->value, NeoPixelType::RGB)
);
echo "  ✓ Status LED (1 pixel) on SPI2\n\n";

// ═══════════════════════════════════════════════════════════════
// Demo 1: Independent Channel Control
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 1: Independent Channel Control\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  🎨 Strip: Red, Fan: Green, Status: Blue\n";
$bus->useSource('strip', 'fill', [0xFF0000]);  // Red
$bus->useSource('fan', 'fill', [0x00FF00]);    // Green
$bus->useSource('status', 'fill', [0x0000FF]); // Blue
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
sleep(2);

// Clear all
$bus->useSource('strip', 'clear', []);
$bus->useSource('fan', 'clear', []);
$bus->useSource('status', 'clear', []);
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 2: Synchronized Color Wave
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 2: Synchronized Color Wave\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$colors = [
    ['name' => 'Red',     'hex' => 0xFF0000],
    ['name' => 'Yellow',  'hex' => 0xFFFF00],
    ['name' => 'Green',   'hex' => 0x00FF00],
    ['name' => 'Cyan',    'hex' => 0x00FFFF],
    ['name' => 'Blue',    'hex' => 0x0000FF],
    ['name' => 'Magenta', 'hex' => 0xFF00FF],
];

foreach ($colors as $color) {
    echo "  🌈 All devices: {$color['name']}\n";
    $bus->useSource('strip', 'fill', [$color['hex']]);
    $bus->useSource('fan', 'fill', [$color['hex']]);
    $bus->useSource('status', 'fill', [$color['hex']]);
    $bus->useSource('strip', 'show', []);
    $bus->useSource('fan', 'show', []);
    $bus->useSource('status', 'show', []);
    usleep(400000);
}

$bus->useSource('strip', 'clear', []);
$bus->useSource('fan', 'clear', []);
$bus->useSource('status', 'clear', []);
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 3: Independent Brightness Control
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 3: Independent Brightness Control\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Set all to white
$bus->useSource('strip', 'fill', [0xFFFFFF]);
$bus->useSource('fan', 'fill', [0xFFFFFF]);
$bus->useSource('status', 'fill', [0xFFFFFF]);

echo "  💡 Strip: 100%, Fan: 50%, Status: 25%\n";
$bus->useSource('strip', 'setBrightness', [255]);
$bus->useSource('fan', 'setBrightness', [128]);
$bus->useSource('status', 'setBrightness', [64]);
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
sleep(2);

echo "  💡 Strip: 25%, Fan: 50%, Status: 100%\n";
$bus->useSource('strip', 'setBrightness', [64]);
$bus->useSource('fan', 'setBrightness', [128]);
$bus->useSource('status', 'setBrightness', [255]);
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
sleep(2);

// Reset brightness
$bus->useSource('strip', 'setBrightness', [255]);
$bus->useSource('fan', 'setBrightness', [255]);
$bus->useSource('status', 'setBrightness', [255]);
$bus->useSource('strip', 'clear', []);
$bus->useSource('fan', 'clear', []);
$bus->useSource('status', 'clear', []);
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 4: Sequential Device Activation
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 4: Sequential Device Activation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  ⚡ Activating Strip...\n";
$bus->useSource('strip', 'fill', [0x00FF00]);
$bus->useSource('strip', 'show', []);
sleep(1);

echo "  ⚡ Activating Fan...\n";
$bus->useSource('fan', 'fill', [0x00FF00]);
$bus->useSource('fan', 'show', []);
sleep(1);

echo "  ⚡ Activating Status...\n";
$bus->useSource('status', 'fill', [0x00FF00]);
$bus->useSource('status', 'show', []);
sleep(1);

echo "  ✓ All devices active!\n";
sleep(1);

// Deactivate in reverse
echo "  💤 Deactivating Status...\n";
$bus->useSource('status', 'clear', []);
$bus->useSource('status', 'show', []);
sleep(1);

echo "  💤 Deactivating Fan...\n";
$bus->useSource('fan', 'clear', []);
$bus->useSource('fan', 'show', []);
sleep(1);

echo "  💤 Deactivating Strip...\n";
$bus->useSource('strip', 'clear', []);
$bus->useSource('strip', 'show', []);
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 5: Status Indicator System
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 5: Status Indicator System\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$statuses = [
    ['name' => 'Idle',      'strip' => 0x404040, 'fan' => 0x000080, 'status' => 0x000080],
    ['name' => 'Processing','strip' => 0xFFFF00, 'fan' => 0xFFFF00, 'status' => 0xFFFF00],
    ['name' => 'Warning',   'strip' => 0xFF8000, 'fan' => 0xFF8000, 'status' => 0xFF8000],
    ['name' => 'Error',     'strip' => 0xFF0000, 'fan' => 0xFF0000, 'status' => 0xFF0000],
    ['name' => 'Success',   'strip' => 0x00FF00, 'fan' => 0x00FF00, 'status' => 0x00FF00],
];

foreach ($statuses as $status) {
    echo "  📡 System Status: {$status['name']}\n";
    
    $bus->useSource('strip', 'fill', [$status['strip']]);
    $bus->useSource('fan', 'fill', [$status['fan']]);
    $bus->useSource('status', 'fill', [$status['status']]);
    $bus->useSource('strip', 'show', []);
    $bus->useSource('fan', 'show', []);
    $bus->useSource('status', 'show', []);
    
    // Error status blinks
    if ($status['name'] === 'Error') {
        for ($i = 0; $i < 3; $i++) {
            usleep(200000);
            $bus->useSource('strip', 'setBrightness', [0]);
            $bus->useSource('fan', 'setBrightness', [0]);
            $bus->useSource('status', 'setBrightness', [0]);
            $bus->useSource('strip', 'show', []);
            $bus->useSource('fan', 'show', []);
            $bus->useSource('status', 'show', []);
            
            usleep(200000);
            $bus->useSource('strip', 'setBrightness', [255]);
            $bus->useSource('fan', 'setBrightness', [255]);
            $bus->useSource('status', 'setBrightness', [255]);
            $bus->useSource('strip', 'show', []);
            $bus->useSource('fan', 'show', []);
            $bus->useSource('status', 'show', []);
        }
    } else {
        sleep(1);
    }
}

$bus->useSource('strip', 'clear', []);
$bus->useSource('fan', 'clear', []);
$bus->useSource('status', 'clear', []);
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 6: Synchronized Breathing
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 6: Synchronized Breathing Effect\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$bus->useSource('strip', 'fill', [0xFF00FF]); // Magenta
$bus->useSource('fan', 'fill', [0xFF00FF]);
$bus->useSource('status', 'fill', [0xFF00FF]);

echo "  💨 Breathing in...\n";
for ($brightness = 0; $brightness <= 255; $brightness += 10) {
    $bus->useSource('strip', 'setBrightness', [$brightness]);
    $bus->useSource('fan', 'setBrightness', [$brightness]);
    $bus->useSource('status', 'setBrightness', [$brightness]);
    $bus->useSource('strip', 'show', []);
    $bus->useSource('fan', 'show', []);
    $bus->useSource('status', 'show', []);
    usleep(30000);
}

echo "  💨 Breathing out...\n";
for ($brightness = 255; $brightness >= 0; $brightness -= 10) {
    $bus->useSource('strip', 'setBrightness', [$brightness]);
    $bus->useSource('fan', 'setBrightness', [$brightness]);
    $bus->useSource('status', 'setBrightness', [$brightness]);
    $bus->useSource('strip', 'show', []);
    $bus->useSource('fan', 'show', []);
    $bus->useSource('status', 'show', []);
    usleep(30000);
}

// Reset
$bus->useSource('strip', 'setBrightness', [255]);
$bus->useSource('fan', 'setBrightness', [255]);
$bus->useSource('status', 'setBrightness', [255]);
$bus->useSource('strip', 'clear', []);
$bus->useSource('fan', 'clear', []);
$bus->useSource('status', 'clear', []);
$bus->useSource('strip', 'show', []);
$bus->useSource('fan', 'show', []);
$bus->useSource('status', 'show', []);
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
echo "║   ✓ Multi-channel management via PixelBus                 ║\n";
echo "║   ✓ Independent channel control                           ║\n";
echo "║   ✓ Synchronized operations                               ║\n";
echo "║   ✓ Independent brightness per channel                    ║\n";
echo "║   ✓ Sequential device activation                          ║\n";
echo "║   ✓ Status indicator system                               ║\n";
echo "║   ✓ Synchronized breathing effect                         ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

