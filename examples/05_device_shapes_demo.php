#!/usr/bin/env php
<?php
/**
 * Example 5: Device Shapes Demo
 * 
 * This example showcases the DeviceShapes classes with their
 * specialized methods tailored for specific LED form factors.
 * 
 * Hardware Setup:
 * - SingleDiode: Status LED on /dev/spidev0.1
 * - DoubleDots: RGB Fan on /dev/spidev1.0  
 * - RGBStrip: 15-pixel strip on /dev/spidev0.0
 * 
 * DeviceShapes demonstrated:
 * - SingleDiode: Status indicators, pulses, blinks
 * - DoubleDots: Spin, alternate, crossfade, bounce
 * - RGBStrip: Waves, sparkles, gradients, fire, comets
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpdaFruit\NeoPixels\DeviceShapes\SingleDiode;
use PhpdaFruit\NeoPixels\DeviceShapes\DoubleDots;
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║           🎨 DEVICE SHAPES SHOWCASE 🎨                    ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Initialize devices
echo "🔧 Initializing shape-specific devices...\n";
$statusLED = new SingleDiode(SPIDevice::SPI_0_1->value, NeoPixelType::RGB);
$fan = new DoubleDots(SPIDevice::SPI_1_0->value, NeoPixelType::GRB);
$strip = new RGBStrip(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);

echo "  ✓ SingleDiode (status LED)\n";
echo "  ✓ DoubleDots (RGB fan)\n";
echo "  ✓ RGBStrip (15 pixels)\n\n";

// ═══════════════════════════════════════════════════════════════
// Part 1: SingleDiode Methods
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Part 1: SingleDiode - Status Indicator Methods\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  💡 Simple on/off...\n";
$statusLED->on(0x00FF00);  // Green on
sleep(1);
$statusLED->off();
sleep(1);

echo "  📡 Status indicators...\n";
$statusLED->status('info');     // Blue blink
sleep(1);
$statusLED->status('success');  // Green blink
sleep(1);
$statusLED->status('warning');  // Yellow blink
sleep(1);
$statusLED->status('error');    // Red blink
sleep(1);

echo "  💨 Pulse (breathing)...\n";
$statusLED->pulse(0x0000FF, 2, 1500);  // Blue breathing, 2 cycles

echo "  ⚡ Strobe...\n";
$statusLED->strobe(0xFFFFFF, 10, 50);  // White strobe

$statusLED->off();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Part 2: DoubleDots Methods
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Part 2: DoubleDots - Dual LED Animations\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  🎨 Split colors...\n";
$fan->split(0xFF0000, 0x0000FF)->show();  // Red left, blue right
sleep(2);

echo "  🔄 Mirror (both same)...\n";
$fan->mirror(0x00FF00)->show();  // Both green
sleep(2);

echo "  🌗 Opposite (complementary)...\n";
$fan->opposite(0xFF0000)->show();  // Red and cyan
sleep(2);

echo "  ↔️  Alternate...\n";
$fan->alternate(0xFF00FF, 5, 200);  // Magenta alternating

echo "  🌪️  Spin effect...\n";
$fan->spin(0x00FFFF, 3, 500);  // Cyan spinning

echo "  🏐 Bounce...\n";
$fan->bounce(0xFFFF00, 5, 150);  // Yellow bouncing

echo "  🌊 Crossfade...\n";
$fan->crossfade(0xFF0000, 0x0000FF, 2, 2000);  // Red/Blue crossfade

$fan->clear()->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Part 3: RGBStrip Methods
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Part 3: RGBStrip - Linear Animations\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  🌈 Gradient fill...\n";
$strip->fillGradient(0xFF0000, 0x0000FF)->show();  // Red to blue
sleep(2);

echo "  🌊 Color wave...\n";
$strip->wave(0x00FFFF, 2, 1000);  // Cyan wave

echo "  ✨ Sparkle...\n";
$strip->sparkle(0xFFFFFF, 2000, 3);  // White sparkles

echo "  📍 Center out...\n";
$strip->centerOut(0x00FF00, 1000);  // Green expanding from center
sleep(1);
$strip->clear()->show();

echo "  📍 Edges in...\n";
$strip->edgesIn(0xFF00FF, 1000);  // Magenta collapsing from edges
sleep(1);
$strip->clear()->show();

echo "  🎭 Theater chase...\n";
$strip->theaterChase(0xFFFF00, 3, 10, 50);  // Yellow theater chase

echo "  🌈 Rainbow static...\n";
$strip->rainbow()->show();
sleep(2);

echo "  🌈 Rainbow cycle...\n";
$strip->rainbowCycle(3, 20);  // Animated rainbow

echo "  🔥 Fire effect...\n";
$strip->fire(3000);  // Fire simulation

echo "  💫 Comet effect...\n";
$strip->comet(0x00FFFF, 3, 5);  // Cyan comets

$strip->clear()->show();
echo "\n";

// ═══════════════════════════════════════════════════════════════
// Part 4: Combined Effects
// ═══════════════════════════════════════════════════════════════
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Part 4: Combined Device Choreography\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  🎬 Scene 1: Startup sequence...\n";
$statusLED->pulse(0x0000FF, 1, 1000);  // Status LED pulses blue
$fan->spin(0x00FF00, 2, 500);          // Fan spins green
$strip->centerOut(0x00FFFF, 1000);     // Strip expands cyan
sleep(1);

echo "  🎬 Scene 2: Alert mode...\n";
$statusLED->blink(0xFF0000, 3, 200, 200);  // Status blinks red
$fan->alternate(0xFF0000, 5, 200);         // Fan alternates red
$strip->theaterChase(0xFF0000, 3, 10, 50); // Strip red chase

echo "  🎬 Scene 3: Party mode...\n";
// Status LED strobes
for ($i = 0; $i < 3; $i++) {
    $statusLED->on(0xFF00FF);
    $fan->opposite(0xFF00FF)->show();
    $strip->fillGradient(0xFF0000, 0x0000FF)->show();
    usleep(200000);
    
    $statusLED->off();
    $fan->clear()->show();
    $strip->clear()->show();
    usleep(200000);
}

echo "  🎬 Scene 4: Shutdown sequence...\n";
$strip->edgesIn(0x0000FF, 1000);  // Strip collapses
$fan->crossfade(0x0000FF, 0x000000, 1, 1000);  // Fan fades out
$statusLED->fadeOut(1000);  // Status fades out

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║               ✨ ALL SHAPES DEMONSTRATED ✨                ║\n";
echo "║                                                            ║\n";
echo "║  SingleDiode Methods:                                      ║\n";
echo "║   ✓ on(), off(), setColor()                               ║\n";
echo "║   ✓ blink(), pulse(), strobe()                            ║\n";
echo "║   ✓ status() - semantic color codes                       ║\n";
echo "║                                                            ║\n";
echo "║  DoubleDots Methods:                                       ║\n";
echo "║   ✓ setLeft(), setRight(), split()                        ║\n";
echo "║   ✓ mirror(), opposite()                                  ║\n";
echo "║   ✓ alternate(), spin(), bounce()                         ║\n";
echo "║   ✓ crossfade()                                           ║\n";
echo "║                                                            ║\n";
echo "║  RGBStrip Methods:                                         ║\n";
echo "║   ✓ fillGradient(), wave(), sparkle()                     ║\n";
echo "║   ✓ centerOut(), edgesIn()                                ║\n";
echo "║   ✓ theaterChase(), rainbow(), rainbowCycle()             ║\n";
echo "║   ✓ fire(), comet()                                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

