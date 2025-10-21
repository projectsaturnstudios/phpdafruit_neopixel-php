#!/usr/bin/env php
<?php
/**
 * Example 4: New Convenience Methods Demo
 * 
 * This example demonstrates all the new convenience methods added to
 * PixelChannel and PixelBus for easier LED control.
 * 
 * Hardware Setup:
 * - LED Strip: 15 pixels on /dev/spidev0.0 (SPI0)
 * - RGB Fan: 2 pixels on /dev/spidev1.0 (SPI1)
 * 
 * New Methods Demonstrated:
 * 
 * PixelBus:
 * - fillAll(), clearAll(), showAll()
 * - setBrightnessAll()
 * - broadcast()
 * - getChannel(), hasChannel(), getChannelNames()
 * 
 * PixelChannel:
 * - setPixelColorHex()
 * - getPixelCount(), getPixels()
 * - rotate(), reverse()
 * - fadeIn(), fadeOut()
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpdaFruit\NeoPixels\PixelBus;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        🎨 NEW CONVENIENCE METHODS DEMO 🎨                 ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Initialize devices
$bus = new PixelBus([
    'strip' => new PixelChannel(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
    'fan' => new PixelChannel(2, SPIDevice::SPI_1_0->value, NeoPixelType::GRB),
]);

echo "🔧 Initialized:\n";
echo "  Channels: " . implode(', ', $bus->getChannelNames()) . "\n\n";

// ═══════════════════════════════════════════════════════════════
// Demo 1: PixelBus Broadcast Methods
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 1: PixelBus fillAll() and showAll()\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$colors = [
    ['name' => 'Red',    'hex' => 0xFF0000],
    ['name' => 'Green',  'hex' => 0x00FF00],
    ['name' => 'Blue',   'hex' => 0x0000FF],
    ['name' => 'Purple', 'hex' => 0xFF00FF],
];

foreach ($colors as $color) {
    echo "  🌈 All devices: {$color['name']}\n";
    $bus->fillAll($color['hex'])->showAll();  // Chainable!
    usleep(500000);
}

$bus->clearAll()->showAll();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 2: PixelChannel Hex Colors
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 2: setPixelColorHex() - Cleaner syntax\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$strip = $bus->getChannel('strip');

echo "  🎨 Setting individual pixels with hex colors...\n";
$strip->setPixelColorHex(0, 0xFF0000);  // Red - much cleaner!
$strip->setPixelColorHex(1, 0x00FF00);  // Green
$strip->setPixelColorHex(2, 0x0000FF);  // Blue
$strip->setPixelColorHex(3, 0xFFFF00);  // Yellow
$strip->setPixelColorHex(4, 0xFF00FF);  // Magenta
$strip->show();
sleep(2);

$strip->clear();
$strip->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 3: Rotate Animation
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 3: rotate() - Perfect for chase effects\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Set up a rainbow pattern
echo "  🌈 Creating rainbow pattern...\n";
for ($i = 0; $i < 7; $i++) {
    $hue = (int)(($i / 7) * 255);
    $color = ($hue << 16) | ((255 - $hue) << 8) | (int)($hue / 2);
    $strip->setPixelColorHex($i, $color);
}
$strip->show();
sleep(1);

echo "  🔄 Rotating...\n";
for ($i = 0; $i < 20; $i++) {
    $strip->rotate(1)->show();  // Rotate right by 1 position
    usleep(100000);
}

$strip->clear()->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 4: Reverse
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 4: reverse() - Mirror effects\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Create a gradient
echo "  📊 Creating gradient...\n";
for ($i = 0; $i < 10; $i++) {
    $brightness = (int)(($i / 10) * 255);
    $color = ($brightness << 16) | ($brightness << 8) | $brightness;
    $strip->setPixelColorHex($i, $color);
}
$strip->show();
sleep(1);

echo "  🔄 Reversing...\n";
$strip->reverse()->show();
sleep(2);

$strip->clear()->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 5: Fade In/Out
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 5: fadeIn() and fadeOut() - Smooth transitions\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$strip->fill(0x00FFFF);  // Cyan
$strip->setBrightness(0);

echo "  💫 Fading in cyan (1 second)...\n";
$strip->fadeIn(1000);

echo "  💫 Holding...\n";
sleep(1);

echo "  💫 Fading out (1 second)...\n";
$strip->fadeOut(1000);

$strip->clear()->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 6: PixelBus setBrightnessAll()
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 6: setBrightnessAll() - Sync all devices\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$bus->fillAll(0xFFFFFF)->showAll();

echo "  💡 Synced brightness sweep...\n";
for ($brightness = 0; $brightness <= 255; $brightness += 10) {
    $bus->setBrightnessAll($brightness)->showAll();
    usleep(30000);
}

for ($brightness = 255; $brightness >= 0; $brightness -= 10) {
    $bus->setBrightnessAll($brightness)->showAll();
    usleep(30000);
}

$bus->setBrightnessAll(255)->clearAll()->showAll();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 7: getPixels() and getPixelCount()
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 7: getPixels() - Read current state\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$strip->setPixelColorHex(0, 0xFF0000);
$strip->setPixelColorHex(1, 0x00FF00);
$strip->setPixelColorHex(2, 0x0000FF);
$strip->show();

echo "  📖 Strip has {$strip->getPixelCount()} pixels\n";
echo "  📖 First 3 pixel colors:\n";
$pixels = $strip->getPixels();
for ($i = 0; $i < 3; $i++) {
    echo "     Pixel {$i}: 0x" . strtoupper(dechex($pixels[$i])) . "\n";
}

sleep(2);
$strip->clear()->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 8: broadcast() - Custom method calls
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 8: broadcast() - Call any method on all channels\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$bus->fillAll(0xFF00FF);  // Magenta
$bus->showAll();

echo "  📡 Broadcasting setBrightness(128)...\n";
$bus->broadcast('setBrightness', [128]);
$bus->showAll();
sleep(1);

echo "  📡 Broadcasting clear()...\n";
$bus->broadcast('clear');
$bus->showAll();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Demo 9: Channel Management
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Demo 9: Channel Management\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  📋 Current channels: " . implode(', ', $bus->getChannelNames()) . "\n";
echo "  ✓ Has 'strip': " . ($bus->hasChannel('strip') ? 'yes' : 'no') . "\n";
echo "  ✓ Has 'missing': " . ($bus->hasChannel('missing') ? 'yes' : 'no') . "\n";

$strip = $bus->getChannel('strip');
if ($strip) {
    echo "  📖 Strip channel retrieved, has {$strip->getPixelCount()} pixels\n";
}

echo "\n";

// ═══════════════════════════════════════════════════════════════
// Finale: Combined Power
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎆 Finale: Combining All New Features\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Create rainbow, rotate it, then fade in
$strip = $bus->getChannel('strip');
for ($i = 0; $i < $strip->getPixelCount(); $i++) {
    $hue = (int)(($i / $strip->getPixelCount()) * 255);
    $color = ($hue << 16) | ((255 - $hue) << 8) | 100;
    $strip->setPixelColorHex($i, $color);
}
$strip->setBrightness(0);

echo "  🌈 Rainbow pattern created...\n";
for ($i = 0; $i < 15; $i++) {
    $strip->rotate(1)->fadeIn(50);
}

echo "  💫 Fading out...\n";
$strip->fadeOut(1000);

$bus->clearAll()->showAll();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║               ✨ ALL DEMOS COMPLETE ✨                     ║\n";
echo "║                                                            ║\n";
echo "║  New PixelBus Methods:                                     ║\n";
echo "║   ✓ fillAll(), clearAll(), showAll()                      ║\n";
echo "║   ✓ setBrightnessAll()                                    ║\n";
echo "║   ✓ broadcast()                                           ║\n";
echo "║   ✓ getChannel(), hasChannel(), getChannelNames()         ║\n";
echo "║                                                            ║\n";
echo "║  New PixelChannel Methods:                                 ║\n";
echo "║   ✓ setPixelColorHex()                                    ║\n";
echo "║   ✓ getPixelCount(), getPixels()                          ║\n";
echo "║   ✓ rotate(), reverse()                                   ║\n";
echo "║   ✓ fadeIn(), fadeOut()                                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

