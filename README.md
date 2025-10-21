# neopixel-php

A modern PHP 8.3+ library for controlling WS281x/NeoPixel LED strips, fans, and devices using the [phpixel extension](https://github.com/projectsaturnstudios/phpdafruit_phpixel_extension). This package provides an elegant, object-oriented API with shape-specific abstractions for common LED configurations.

## Features

- **Object-Oriented API**: Clean, fluent interface for LED control
- **Multi-Device Management**: Control multiple LED devices simultaneously with PixelBus
- **Shape-Specific Classes**: Pre-built abstractions for common LED configurations
- **Type-Safe Enums**: Convenient enums for pixel types and SPI devices
- **Chainable Methods**: Fluent API for readable, expressive code
- **Extensible Architecture**: Easy to create custom device shapes
- **Comprehensive Testing**: Full Pest v4 test suite included

## Requirements

- PHP 8.3 or higher
- phpixel extension installed
- Embedded Linux device with SPI support (Raspberry Pi 5, Jetson Orin Nano, etc.)
- SPI device nodes present (e.g., `/dev/spidev0.0`, `/dev/spidev1.0`)

## Installation

### Install via Composer

```bash
composer require phpdafruit/neopixel-php
```

### Install phpixel Extension

Before using this library, you must install the phpixel extension on your embedded Linux device. See the [phpixel extension installation guide](https://github.com/projectsaturnstudios/phpdafruit_phpixel_extension) for complete instructions.

## Core Concepts

### PixelChannel

A `PixelChannel` is an object-oriented wrapper around the phpixel extension's `NeoPixel` class. It represents a single controllable LED device (strip, fan, or individual LED) connected to a specific SPI device.

#### Basic Usage

```php
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

// Create a 30-LED strip on SPI device 0.0 with GRB color order
$strip = new PixelChannel(30, SPIDevice::SPI_0_0->value, NeoPixelType::GRB);

// Initialize the device
$strip->begin();

// Set all pixels to red
$strip->fill(0xFF0000)->show();

// Set individual pixel (pixel 10 to green)
$strip->setPixelColor(10, 0, 255, 0);
$strip->show();

// Control brightness (0-255)
$strip->setBrightness(128)->show();

// Clear all pixels
$strip->clear()->show();
```

#### Available Methods

**Core Operations:**
- `begin()` - Initialize the LED device
- `show()` - Update the physical LEDs with current buffer
- `clear()` - Turn off all LEDs
- `fill(int $color, int $first = 0, int $count = 0)` - Fill pixels with color
- `setPixelColor(int $n, int $r, int $g, int $b, int $w = 0)` - Set individual pixel
- `setPixelColorHex(int $pixel, int $hexColor)` - Set pixel using hex color

**Brightness & Configuration:**
- `setBrightness(int $b)` - Set brightness (0-255)
- `getBrightness()` - Get current brightness
- `updateLength(int $n)` - Change number of pixels
- `updateType(NeoPixelType $type)` - Change color order
- `setDevicePath(string $path)` - Change SPI device

**Information:**
- `getPixelCount()` - Get number of pixels
- `getPixelColor(int $n)` - Get packed color value
- `getPixels()` - Get array of all pixel colors

**Effects & Animations:**
- `rotate(int $positions = 1)` - Rotate pixel buffer
- `reverse()` - Reverse pixel order
- `fadeIn(int $duration_ms = 1000)` - Fade in effect
- `fadeOut(int $duration_ms = 1000)` - Fade out effect
- `animate(Animation $animation, int $duration_ms, array $options = [])` - Play built-in animations

All methods that modify state return `$this` for method chaining.

### PixelBus

A `PixelBus` manages multiple `PixelChannel` instances, allowing you to control multiple LED devices as a coordinated system. Each channel is identified by a unique name.

#### Basic Usage

```php
use PhpdaFruit\NeoPixels\PixelBus;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

// Create a bus with multiple channels
$bus = new PixelBus([
    'front_strip' => new PixelChannel(30, SPIDevice::SPI_0_0->value, NeoPixelType::GRB),
    'rear_strip' => new PixelChannel(30, SPIDevice::SPI_0_1->value, NeoPixelType::GRB),
    'cpu_fan' => new PixelChannel(12, SPIDevice::SPI_1_0->value, NeoPixelType::RGB),
]);

// Initialize all devices
$bus->broadcast('begin');

// Fill all devices with red
$bus->fillAll(0xFF0000)->showAll();

// Control individual channels
$bus->useSource('front_strip', 'fill', [0x00FF00]);
$bus->useSource('cpu_fan', 'fill', [0x0000FF]);
$bus->showAll();

// Broadcast brightness to all channels
$bus->setBrightnessAll(128)->showAll();

// Clear all devices
$bus->clearAll()->showAll();
```

#### Available Methods

**Channel Management:**
- `addChannel(string $name, PixelChannel $channel)` - Add a new channel
- `removeChannel(string $name)` - Remove a channel
- `getChannel(string $name)` - Get specific channel
- `hasChannel(string $name)` - Check if channel exists
- `getChannelNames()` - Get all channel names

**Operations:**
- `useSource(string $name, string $method, array $args = [])` - Call method on specific channel
- `broadcast(string $method, array $args = [])` - Call method on all channels
- `fillAll(int $color)` - Fill all channels with color
- `clearAll()` - Clear all channels
- `showAll()` - Update all channels
- `setBrightnessAll(int $brightness)` - Set brightness on all channels

## Device Shapes

Device shapes are specialized classes that extend `PixelChannel` with methods tailored to specific LED configurations. They provide semantic, shape-aware APIs for common use cases.

### SingleDiode

Represents a single LED, perfect for status indicators, notifications, and simple alerts.

```php
use PhpdaFruit\NeoPixels\DeviceShapes\SingleDiode;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

$led = new SingleDiode(SPIDevice::SPI_0_1->value);

// Basic control
$led->on(0xFF0000);              // Turn on red
$led->off();                     // Turn off
$led->setColor(0x00FF00);        // Set color without updating

// Effects
$led->blink(0xFF0000, 3, 200);   // Blink red 3 times, 200ms intervals
$led->pulse(0x0000FF, 2, 1500);  // Blue breathing effect, 2 cycles
$led->strobe(0xFFFFFF, 10, 50);  // Fast white strobe

// Semantic status indicators
$led->status('success');         // Green double-blink
$led->status('error');           // Red double-blink
$led->status('warning');         // Yellow double-blink
$led->status('info');            // Blue double-blink
```

### DoubleDots

Represents two LEDs, ideal for PC fans, dual indicators, or side-by-side effects.

```php
use PhpdaFruit\NeoPixels\DeviceShapes\DoubleDots;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

$fan_lights = new DoubleDots(SPIDevice::SPI_1_0->value, NeoPixelType::GRB);

// Position control
$fan_lights->setLeft(0xFF0000);         // Left LED red
$fan_lights->setRight(0x0000FF);        // Right LED blue
$fan_lights->split(0xFF0000, 0x0000FF); // Different colors
$fan_lights->mirror(0x00FF00);          // Both same green

// Animations
$fan_lights->alternate(0xFF00FF, 5);    // Alternate between LEDs 5 times
$fan_lights->spin(0x00FFFF, 3, 500);    // Simulate spinning effect
$fan_lights->bounce(0xFFFF00, 5);       // Ping-pong effect
$fan_lights->crossfade(0xFF0000, 0x0000FF, 2, 2000); // Fade between colors
```

### RGBStrip

Represents a linear LED strip, perfect for underglow, room lighting, and complex animations.

```php
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

$strip = new RGBStrip(30, SPIDevice::SPI_0_0->value);

// Fill patterns
$strip->fillGradient(0xFF0000, 0x0000FF); // Red to blue gradient
$strip->rainbow();                         // Static rainbow

// Directional effects
$strip->centerOut(0x00FF00, 1000);         // Expand from center
$strip->edgesIn(0xFF00FF, 1000);           // Collapse from edges

// Animated effects
$strip->wave(0x00FFFF, 3, 1000);           // Cyan wave
$strip->sparkle(0xFFFFFF, 2000, 3);        // White sparkles
$strip->theaterChase(0xFFFF00, 3, 10);     // Yellow chase effect
$strip->rainbowCycle(5, 50);               // Animated rainbow
$strip->fire(3000);                        // Fire simulation
$strip->comet(0x00FFFF, 3, 5);             // Cyan comets with tail
```

### Creating Custom Shapes

You can extend `PixelChannel` to create custom device shapes for your specific LED configurations:

```php
use PhpdaFruit\NeoPixels\PixelChannel;

class CustomRing extends PixelChannel
{
    public function __construct(string $devicePath = '/dev/spidev0.0')
    {
        // Define your LED count and type
        parent::__construct(16, $devicePath, NeoPixelType::GRB);
    }

    public function spinClockwise(int $color = 0xFFFFFF, int $speed_ms = 50): static
    {
        for ($i = 0; $i < $this->getPixelCount(); $i++) {
            $this->clear();
            $this->setPixelColor($i, ($color >> 16) & 0xFF, ($color >> 8) & 0xFF, $color & 0xFF);
            $this->show();
            usleep($speed_ms * 1000);
        }
        return $this;
    }

    public function breatheSection(int $start, int $end, int $color, int $duration_ms = 2000): static
    {
        // Your custom animation logic
        return $this;
    }
}
```

Potential custom shapes include:
- `Ring` - Circular LED arrangements
- `Matrix` - 2D grid layouts
- `Star` - Multi-point star configurations
- `Square` - Four-corner arrangements
- Any other LED configuration specific to your project

## Animations

The library includes **35 built-in animations** that can be played on any `PixelChannel` or device shape using the `animate()` method.

### Quick Start

```php
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\AnimationRegistry;

// Initialize animation system
AnimationRegistry::initialize();

$strip = new RGBStrip(30, '/dev/spidev0.0');
$strip->begin();

// Play a built-in animation
$strip->animate(Animation::RAINBOW, 5000); // 5 seconds
$strip->animate(Animation::FIRE_FLICKER, 10000, [
    'intensity' => 0.9,
    'cooling' => 60
]);
```

### Available Animations

The library includes animations in these categories:

- **Basic Effects** (15): Static, Blink, Breathe, Wipe, Rainbow, Scan, Fade, and more
- **Sparkle & Twinkle** (7): Twinkle, Sparkle, Sparkle Dark, Sparkle Plus, Strobe, Blink Rainbow
- **Chase & Running** (9): Running, Running Color, Running Red/Blue, Larson Scanner, Theater Chase variants
- **Fire & Heat** (1): Fire Flicker with realistic simulation
- **Comet & Meteor** (2): Comet, Meteor Shower
- **Fireworks** (1): Fireworks explosion effects  
- **Wave & Motion** (2): Saw, Rain
- **Physics** (2): Dissolve, Dissolve Random
- **Gradient** (1): Smooth flowing gradients

For complete documentation of all 35 animations with usage examples and customization options, see **[ANIMATIONS.md](ANIMATIONS.md)**.

### Animation Architecture

The animation system consists of:

- **Animation Enum** (`PhpdaFruit\NeoPixels\Enums\Animation`) - Type-safe animation identifiers
- **AnimationVisualization Interface** - Contract for animation implementations
- **AnimationFactory** - Factory pattern for creating animations
- **AnimationRegistry** - Central registration system
- **Effect Traits** - Reusable logic (FadeEffect, ColorWheelEffect, TimingEffect, RandomEffect)

### Custom Animations

Create custom animations by implementing `AnimationVisualization`:

```php
use PhpdaFruit\NeoPixels\Contracts\Animations\Visualizations\AnimationVisualization;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\PixelChannel;

class MyCustomVisualization implements AnimationVisualization
{
    public function run(PixelChannel $channel, int $duration_ms, array $options = []): void
    {
        // Your animation logic here
    }

    public function getAnimationType(): Animation
    {
        return Animation::CUSTOM;
    }

    public function getName(): string
    {
        return 'My Custom Animation';
    }

    public function getDefaultOptions(): array
    {
        return ['speed' => 100, 'color' => 0xFF0000];
    }

    public function isCompatible(PixelChannel $channel): bool
    {
        return true; // Or add specific requirements
    }
}
```

Register your custom animation:

```php
use PhpdaFruit\NeoPixels\AnimationFactory;

AnimationFactory::register(Animation::CUSTOM, MyCustomVisualization::class);
```

## Enums

The library provides type-safe enums for improved code clarity and IDE support.

### NeoPixelType

Defines the color channel order for your LED devices. Different LED models use different orders.

```php
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

NeoPixelType::RGB   // Red, Green, Blue (no white)
NeoPixelType::GRB   // Green, Red, Blue (most common, e.g., WS2812B)
NeoPixelType::RGBW  // Red, Green, Blue, White
NeoPixelType::GRBW  // Green, Red, Blue, White
```

**Usage:**

```php
// Using the enum
$strip = new PixelChannel(30, SPIDevice::SPI_0_0->value, NeoPixelType::GRB);

// Update color order later
$strip->updateType(NeoPixelType::RGBW);
```

### SPIDevice

Provides convenient access to common SPI device paths.

```php
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

SPIDevice::SPI_0_0  // /dev/spidev0.0
SPIDevice::SPI_0_1  // /dev/spidev0.1
SPIDevice::SPI_1_0  // /dev/spidev1.0
SPIDevice::SPI_1_1  // /dev/spidev1.1
SPIDevice::SPI_2_0  // /dev/spidev2.0
// ... through SPI_4_1
```

**Benefits:**
- **Autocomplete**: IDE support for available devices
- **Type Safety**: Prevents typos in device paths
- **Readability**: `SPIDevice::SPI_0_0->value` is clearer than `'/dev/spidev0.0'`
- **Consistency**: Ensures correct device path format

**Usage:**

```php
// With enum (recommended)
$strip = new PixelChannel(30, SPIDevice::SPI_0_0->value, NeoPixelType::GRB);

// Without enum (also valid)
$strip = new PixelChannel(30, '/dev/spidev0.0', NeoPixelType::GRB);
```

## Testing

The library includes a comprehensive Pest v4 test suite covering unit tests, integration tests, and hardware scenarios.

### Running Tests

```bash
# Run all tests
composer test

# Run tests quietly (recommended - avoids post-test segfault output)
composer test:quiet

# Run with coverage report
composer test:coverage

# Run specific test file
./vendor/bin/pest tests/Unit/PixelChannelTest.php

# Run tests with verbose output
./vendor/bin/pest --verbose
```

### Test Structure

```
tests/
├── Pest.php                          # Test configuration
├── Unit/
│   ├── PixelChannelTest.php         # PixelChannel unit tests
│   └── PixelBusTest.php             # PixelBus unit tests
└── Integration/
    └── HardwareTest.php             # Real hardware integration tests
```

### Notes on Testing

- Tests require the phpixel extension to be loaded
- Some tests interact with real hardware and require available SPI devices
- The test suite uses Mockery for mocking when appropriate
- Integration tests are marked as such and can be skipped if hardware is not available

## Examples

The package includes five comprehensive examples demonstrating various features and use cases.

### Running Examples

All examples are located in the `examples/` directory and can be run directly via PHP CLI on your embedded device:

```bash
# Example 1: Basic single channel usage
php examples/01_single_channel_basic.php

# Example 2: Multi-channel control with PixelBus
php examples/02_pixel_bus_multi_channel.php

# Example 3: Advanced multi-device animations
php examples/03_advanced_animations.php

# Example 4: New convenience methods
php examples/04_new_convenience_methods.php

# Example 5: Device shapes demonstrations
php examples/05_device_shapes_demo.php
```

### Example Overview

**01_single_channel_basic.php**
- Basic PixelChannel operations
- Color fills and individual pixel control
- Brightness control
- Simple effects (fade, rotate, reverse)

**02_pixel_bus_multi_channel.php**
- Creating and managing multiple channels
- Synchronized operations across devices
- Individual channel control
- Broadcast methods

**03_advanced_animations.php**
- Complex multi-device animations
- Synchronized color waves
- Chase effects across multiple strips
- Coordinated timing patterns

**04_new_convenience_methods.php**
- Demonstrates `fillAll()`, `showAll()`, `clearAll()`
- Channel management methods
- Hex color utilities
- Advanced effects

**05_device_shapes_demo.php**
- SingleDiode status indicators and effects
- DoubleDots animations (spin, bounce, crossfade)
- RGBStrip complex patterns (rainbow, fire, comet)
- Real-world usage patterns

### Hardware Requirements for Examples

Most examples expect at least one LED device connected to an available SPI port. Adjust the device paths and pixel counts in the examples to match your hardware configuration.

## Troubleshooting

### Extension Not Found

```
PHP Fatal error: Uncaught Error: Class 'NeoPixel' not found
```

**Solution**: Install the [phpixel extension](https://github.com/projectsaturnstudios/phpdafruit_phpixel_extension) first.

### SPI Device Not Found

```
MicrocontrollerException: Failed to open SPI device
```

**Solution**: 
- Verify SPI is enabled on your device
- Check that device paths exist: `ls /dev/spidev*`
- Ensure your user has permission to access SPI devices (add user to `spi` group)
- For Raspberry Pi 5 SPI1, enable with `dtoverlay=spi1-3cs` in `/boot/firmware/config.txt`

### Wrong Colors

If colors appear incorrect (e.g., red shows as green), you're using the wrong color order.

**Solution**: Try different `NeoPixelType` values:
```php
$strip->updateType(NeoPixelType::GRB);  // Most common
$strip->updateType(NeoPixelType::RGB);  // Alternative
```

## License

MIT License. See LICENSE file for details.

## Credits

This library wraps the [phpixel extension](https://github.com/projectsaturnstudios/phpdafruit_phpixel_extension), which provides the low-level SPI interface for WS281x LED control.

## Contributing

Contributions are welcome. When contributing:
- Follow PSR-12 coding standards
- Add tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting pull requests

## Support

For issues related to:
- **This library**: Open an issue in this repository
- **phpixel extension**: See the [phpixel repository](https://github.com/projectsaturnstudios/phpdafruit_phpixel_extension)
- **Hardware setup**: Consult your device's SPI configuration documentation

---

## Changelog

### v2.0.0 - Animation System Release (Current)

**Major Features:**
- **35 Built-in Animations** - Comprehensive animation library with 35 pre-built effects
- **Animation System Architecture** - Factory pattern, registry, and visualization interfaces
- **Effect Traits** - Reusable animation logic (FadeEffect, ColorWheelEffect, TimingEffect, RandomEffect)
- **Animation Enum** - Type-safe animation identifiers with metadata
- **RGBStrip Enhancements** - Added `flip()` method for reversed pixel order
- **LaravelRGB Integration** - Full Laravel wrapper package (separate repository)

**New Animations:**
- Basic: Static, Blink, Breathe, Wipe, Wipe Random, Random Colors, Sweep, Dynamic, Colorloop, Rainbow, Scan, Scan Dual, Fade, Theater Chase, Theater Chase Rainbow
- Sparkle: Twinkle, Sparkle, Sparkle Dark, Sparkle Plus, Strobe, Blink Rainbow
- Chase/Running: Running, Running Color, Running Red/Blue, Running Random, Larson Scanner, Chase Flash, Chase Rainbow White, Chase Blackout, Chase Blackout Rainbow
- Special: Fire Flicker, Comet, Meteor Shower, Fireworks, Saw, Rain, Dissolve, Dissolve Random, Gradient

**API Additions:**
- `PixelChannel::animate(Animation $animation, int $duration_ms, array $options = [])` - Play animations
- `RGBStrip::flip()` - Toggle pixel reversal for upside-down mounting
- `RGBStrip::setReversed(bool $reversed)` - Explicitly set pixel order
- `RGBStrip::isReversed()` - Check if pixels are reversed
- `AnimationRegistry::initialize()` - Initialize animation system
- `AnimationFactory::register()` - Register custom animations

**Documentation:**
- Added ANIMATIONS.md with complete animation reference
- Updated README with Animations section
- Enhanced examples and use cases

**Breaking Changes:**
- None - fully backward compatible with v1.x

---

### v1.0.0 - Initial Release

**Core Features:**
- Object-oriented API for NeoPixel control
- PixelChannel abstraction for single LED devices
- PixelBus for multi-device management
- Device shapes: SingleDiode, DoubleDots, RGBStrip
- Type-safe enums: NeoPixelType, SPIDevice
- Fluent/chainable method interface
- Comprehensive Pest test suite
- 5 example scripts demonstrating features

**Supported Hardware:**
- Raspberry Pi 5 (and earlier models)
- NVIDIA Jetson Orin Nano
- Any embedded Linux device with SPI support

**Requirements:**
- PHP 8.3+
- phpixel extension
- SPI device nodes (/dev/spidev*)

For a complete feature list and usage examples from v1.0.0, see the Device Shapes and Examples sections above.

