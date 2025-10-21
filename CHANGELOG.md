# Changelog

All notable changes to the neopixel-php library will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Animation System**: Complete animation framework with 35+ WLED-inspired effects
  - Animation enum with 180+ WLED effects (35 currently implemented)
  - AnimationFactory for creating and registering animations
  - AnimationRegistry for managing available animations
  - `animate()` method on PixelChannel for easy animation playback
- **Animation Effects (Reusable Traits)**:
  - `FadeEffect` - Color fading and dimming utilities
  - `ColorWheelEffect` - Rainbow and hue-based color generation
  - `TimingEffect` - Duration-based animation timing and loops
  - `RandomEffect` - Random color and value generation
- **35 Built-in Animations**:
  - **Basic**: Static, Blink, Breathe, Wipe, WipeRandom, RandomColors, Sweep, Dynamic, Colorloop, Rainbow, Scan, ScanDual, Fade, TheaterChase, TheaterChaseRainbow
  - **Sparkle & Twinkle**: Sparkle, Twinkle, SparkleDark, SparkPlus, Strobe, BlinkRainbow
  - **Chase & Running**: Running, RunningColor, RunningRedBlue, RunningRandom, LarsonScanner, ChaseFlash, ChaseRainbowWhite, ChaseBlackout, ChaseBlackoutRainbow
  - **Fire & Heat**: FireFlicker
  - **Comet & Meteor**: Comet, MeteorShower
  - **Fireworks**: Fireworks
  - **Wave & Motion**: Saw, Rain
  - **Dissolve & Gradient**: Dissolve, DissolveRandom, Gradient
- **RGBStrip Enhancements**:
  - `flip()` method to reverse pixel order (useful for upside-down mounting)
  - `setReversed()` and `isReversed()` for explicit pixel order control
  - All animations automatically respect flipped orientation

### Changed
- Enhanced RGBStrip with pixel reversal capabilities
- Improved animation architecture with abstract base classes and interfaces

### Documentation
- Added comprehensive ANIMATIONS.md with usage examples for all animations
- Updated README.md with animations section
- Added animation categories and descriptions

## [1.0.0] - Initial Release

### Added
- **Core Classes**:
  - `PixelChannel` - Object-oriented wrapper for phpixel NeoPixel control
  - `PixelBus` - Multi-device management system
- **Device Shapes**:
  - `SingleDiode` - Single LED with status indicators and effects
  - `DoubleDots` - Two-LED devices (fans, dual indicators) with spin/bounce animations
  - `RGBStrip` - Linear LED strips with gradients, waves, and complex effects
- **Enums**:
  - `NeoPixelType` - Color channel order (RGB, GRB, RGBW, GRBW)
  - `SPIDevice` - SPI device path constants
- **Features**:
  - Fluent, chainable API
  - Method chaining for all state-modifying operations
  - Brightness control (0-255)
  - Pixel rotation and reversal
  - Fade in/out effects
  - Color utilities (hex colors, gradients)
- **Examples**:
  - Single channel basic usage
  - Multi-channel PixelBus control
  - Advanced animations
  - Convenience methods
  - Device shapes demonstrations
- **Testing**:
  - Pest v4 test suite
  - Unit tests for PixelChannel and PixelBus
  - Integration tests for hardware
  - Mockery support for isolated testing
- **Documentation**:
  - Comprehensive README with usage examples
  - API documentation for all classes
  - Troubleshooting guide
  - Hardware setup instructions

### Requirements
- PHP 8.3+
- phpixel extension
- Embedded Linux with SPI support
- SPI device nodes (/dev/spidev*)

[Unreleased]: https://github.com/phpdafruit/neopixel-php/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/phpdafruit/neopixel-php/releases/tag/v1.0.0

