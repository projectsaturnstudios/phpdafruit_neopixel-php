# Animation Reference Guide

This guide documents all 35 built-in animations available in the neopixel-php package. Each animation can be triggered using the `animate()` method on any `PixelChannel` or specialized device shape.

## Quick Start

```php
use PhpdaFruit\NeoPixels\DeviceShapes\RGBStrip;
use PhpdaFruit\NeoPixels\Enums\Animation;
use PhpdaFruit\NeoPixels\AnimationRegistry;

// IMPORTANT: Initialize the animation system first (do this once)
AnimationRegistry::initialize();

$strip = new RGBStrip(30, '/dev/spidev0.0');
$strip->begin();

// Play an animation
$strip->animate(Animation::RAINBOW, 5000); // 5 seconds
```

**Note:** You must call `AnimationRegistry::initialize()` once before using any animations. This registers all 35 built-in animations with the system.

## Table of Contents

- [Basic Effects (15)](#basic-effects)
- [Sparkle & Twinkle Effects (7)](#sparkle--twinkle-effects)
- [Chase & Running Effects (9)](#chase--running-effects)
- [Fire & Heat Effects (1)](#fire--heat-effects)
- [Comet & Meteor Effects (2)](#comet--meteor-effects)
- [Fireworks & Explosions (1)](#fireworks--explosions)
- [Wave & Motion Effects (2)](#wave--motion-effects)
- [Physics & Simulation (2)](#physics--simulation)
- [Gradient & Blend Effects (1)](#gradient--blend-effects)

---

## Basic Effects

### STATIC
**Solid color fill**

```php
$strip->animate(Animation::STATIC, 3000, [
    'color' => 0xFF0000  // Red
]);
```

**Options:**
- `color` (int): Hex color value (default: `0xFFFFFF`)

**Use Cases:** Background lighting, solid indicators, ambient lighting

---

### BLINK
**Simple on/off blinking**

```php
$strip->animate(Animation::BLINK, 5000, [
    'color' => 0x00FF00,
    'interval' => 500  // 500ms on, 500ms off
]);
```

**Options:**
- `color` (int): Hex color (default: `0xFFFFFF`)
- `interval` (int): Blink interval in ms (default: `500`)

**Use Cases:** Attention-getting, alerts, notifications

---

### BREATHE
**Smooth fade in and out (breathing effect)**

```php
$strip->animate(Animation::BREATHE, 10000, [
    'color' => 0x0000FF,
    'breath_speed' => 2000  // 2 seconds per breath
]);
```

**Options:**
- `color` (int): Base color (default: `0xFFFFFF`)
- `breath_speed` (int): Duration of one breath cycle in ms (default: `2000`)

**Use Cases:** Idle indicators, sleep mode, ambient mood lighting

---

### WIPE
**Fills strip from one end to the other**

```php
$strip->animate(Animation::WIPE, 3000, [
    'color' => 0xFF00FF,
    'direction' => 'forward'  // or 'reverse'
]);
```

**Options:**
- `color` (int): Fill color (default: `0xFF0000`)
- `direction` (string): `'forward'` or `'reverse'` (default: `'forward'`)

**Use Cases:** Progress indicators, loading animations, transitions

---

### WIPE_RANDOM
**Wipe with random colors**

```php
$strip->animate(Animation::WIPE_RANDOM, 5000);
```

**Options:**
- `direction` (string): `'forward'` or `'reverse'` (default: `'forward'`)

**Use Cases:** Colorful transitions, party mode, randomized effects

---

### RANDOM_COLORS
**Each pixel gets a random color**

```php
$strip->animate(Animation::RANDOM_COLORS, 5000, [
    'change_interval' => 200  // Change every 200ms
]);
```

**Options:**
- `change_interval` (int): Time between color changes in ms (default: `500`)

**Use Cases:** Party mode, disco effects, chaotic displays

---

### SWEEP
**Single dot sweeps back and forth**

```php
$strip->animate(Animation::SWEEP, 5000, [
    'color' => 0xFFFF00,
    'speed' => 50  // 50ms per pixel
]);
```

**Options:**
- `color` (int): Dot color (default: `0xFF0000`)
- `speed` (int): Movement speed in ms per pixel (default: `50`)

**Use Cases:** Scanner effect, progress indication, motion effects

---

### DYNAMIC
**Random pixels with smooth fading**

```php
$strip->animate(Animation::DYNAMIC, 10000, [
    'density' => 0.3,  // 30% of pixels active
    'fade_speed' => 0.95
]);
```

**Options:**
- `density` (float): Probability of pixel activation (0-1, default: `0.2`)
- `fade_speed` (float): Fade factor per frame (default: `0.9`)

**Use Cases:** Ambient effects, dynamic backgrounds, organic patterns

---

### COLORLOOP
**Cycles through hue spectrum**

```php
$strip->animate(Animation::COLORLOOP, 10000, [
    'speed' => 100  // 100ms per hue step
]);
```

**Options:**
- `speed` (int): Hue change speed in ms (default: `50`)

**Use Cases:** Color cycling, rainbow effects without spatial distribution

---

### RAINBOW
**Static rainbow across strip**

```php
$strip->animate(Animation::RAINBOW, 5000, [
    'cycles' => 3  // 3 full rainbow cycles across strip
]);
```

**Options:**
- `cycles` (int): Number of rainbow cycles (default: `1`)

**Use Cases:** Pride displays, colorful backgrounds, testing

---

### SCAN
**KITT/Cylon scanner effect**

```php
$strip->animate(Animation::SCAN, 5000, [
    'color' => 0xFF0000,
    'eye_size' => 4  // Width of scanning eye
]);
```

**Options:**
- `color` (int): Scanner color (default: `0xFF0000`)
- `eye_size` (int): Number of lit pixels (default: `3`)
- `fade_rate` (float): Trail fade speed (default: `0.8`)

**Use Cases:** Retro effects, scanning animations, Cylon/KITT homages

---

### SCAN_DUAL
**Two scanners moving from ends to center and back**

```php
$strip->animate(Animation::SCAN_DUAL, 5000, [
    'color' => 0x00FFFF,
    'eye_size' => 3
]);
```

**Options:**
- `color` (int): Scanner color (default: `0xFF0000`)
- `eye_size` (int): Width of each eye (default: `3`)
- `fade_rate` (float): Trail fade (default: `0.75`)

**Use Cases:** Symmetrical effects, dual scanners, bouncing animations

---

### FADE
**Smooth color transitions**

```php
$strip->animate(Animation::FADE, 5000, [
    'colors' => [0xFF0000, 0x00FF00, 0x0000FF],
    'transition_time' => 2000  // 2 seconds per transition
]);
```

**Options:**
- `colors` (array): Array of colors to fade through (default: `[0xFF0000, 0x0000FF]`)
- `transition_time` (int): Fade duration in ms (default: `1000`)

**Use Cases:** Smooth color changes, ambient lighting, mood transitions

---

### THEATER_CHASE
**Theater marquee chase effect**

```php
$strip->animate(Animation::THEATER_CHASE, 5000, [
    'color' => 0xFFFF00,
    'spacing' => 3  // Every 3rd pixel
]);
```

**Options:**
- `color` (int): Chase color (default: `0xFF0000`)
- `spacing` (int): Pixels between lit pixels (default: `3`)
- `speed` (int): Movement speed in ms (default: `100`)

**Use Cases:** Marquee signs, chase lights, classic effects

---

### THEATER_CHASE_RAINBOW
**Rainbow theater chase**

```php
$strip->animate(Animation::THEATER_CHASE_RAINBOW, 5000, [
    'spacing' => 3,
    'speed' => 100
]);
```

**Options:**
- `spacing` (int): Pixels between lit pixels (default: `3`)
- `speed` (int): Movement speed in ms (default: `100`)

**Use Cases:** Colorful marquees, rainbow chase effects

---

## Sparkle & Twinkle Effects

### TWINKLE
**Random pixels twinkle like stars**

```php
$strip->animate(Animation::TWINKLE, 10000, [
    'color' => 0xFFFFFF,
    'density' => 0.1,  // 10% of pixels twinkle
    'fade_speed' => 0.9
]);
```

**Options:**
- `color` (int): Twinkle color (default: `0xFFFFFF`)
- `density` (float): Probability per frame (0-1, default: `0.1`)
- `fade_speed` (float): Fade factor (default: `0.9`)

**Use Cases:** Starfield effects, gentle sparkles, night sky simulation

---

### SPARKLE
**Random bright flashes**

```php
$strip->animate(Animation::SPARKLE, 5000, [
    'color' => 0xFFFFFF,
    'background' => 0x000040,  // Dark blue background
    'density' => 3  // 3 sparkles per frame
]);
```

**Options:**
- `color` (int): Sparkle color (default: `0xFFFFFF`)
- `background` (int): Background color (default: `0x000000`)
- `density` (int): Number of sparkles per frame (default: `2`)

**Use Cases:** Magic effects, glitter, celebration displays

---

### SPARKLE_DARK
**Sparkles on black background only**

```php
$strip->animate(Animation::SPARKLE_DARK, 5000, [
    'color' => 0xFFFFFF,
    'density' => 3,
    'flash_duration_ms' => 50
]);
```

**Options:**
- `color` (int): Sparkle color (default: `0xFFFFFF`)
- `density` (int): Sparkles per frame (default: `3`)
- `flash_duration_ms` (int): Flash length (default: `50`)

**Use Cases:** Subtle effects, minimalist sparkles, dark themes

---

### SPARKLE_PLUS
**Sparkles with color bleeding to adjacent pixels**

```php
$strip->animate(Animation::SPARKLE_PLUS, 5000, [
    'color' => 0xFFFFFF,
    'density' => 2,
    'bleed_distance' => 2  // Affects 2 pixels on each side
]);
```

**Options:**
- `color` (int): Base color (default: `0xFFFFFF`)
- `density` (int): Sparkles per frame (default: `2`)
- `bleed_distance` (int): Pixel spread (default: `2`)

**Use Cases:** Intense sparkles, bloom effects, highlighted sparkles

---

### STROBE
**Fast strobe light effect**

```php
$strip->animate(Animation::STROBE, 3000, [
    'color' => 0xFFFFFF,
    'on_time' => 50,
    'off_time' => 100
]);
```

**Options:**
- `color` (int): Strobe color (default: `0xFFFFFF`)
- `on_time` (int): On duration in ms (default: `50`)
- `off_time` (int): Off duration in ms (default: `100`)

**Use Cases:** Party effects, attention-grabbing, emergency signals

---

### BLINK_RAINBOW
**Blink through rainbow colors**

```php
$strip->animate(Animation::BLINK_RAINBOW, 5000, [
    'interval' => 200,  // 200ms per color
    'hue_step' => 10  // Hue change per blink
]);
```

**Options:**
- `interval` (int): Time per color in ms (default: `200`)
- `hue_step` (int): Hue increment (default: `10`)

**Use Cases:** Rainbow cycles, color testing, colorful blinks

---

## Chase & Running Effects

### RUNNING
**Single dot racing back and forth**

```php
$strip->animate(Animation::RUNNING, 5000, [
    'color' => 0xFF0000,
    'speed' => 50,
    'trail_length' => 0  // No trail
]);
```

**Options:**
- `color` (int): Dot color (default: `0xFF0000`)
- `speed` (int): ms per pixel (default: `50`)
- `trail_length` (int): Trail pixels (default: `0`)

**Use Cases:** Racing effects, simple motion, bouncing dot

---

### RUNNING_COLOR
**Running dot with fading color trail**

```php
$strip->animate(Animation::RUNNING_COLOR, 5000, [
    'color' => 0xFF00FF,
    'speed' => 50,
    'fade_factor' => 0.85
]);
```

**Options:**
- `color` (int): Dot color (default: `0xFF0000`)
- `speed` (int): ms per pixel (default: `50`)
- `fade_factor` (float): Trail fade (default: `0.85`)

**Use Cases:** Colorful trails, motion with history, smooth racing

---

### RUNNING_RED_BLUE
**Red and blue dots running in opposite directions**

```php
$strip->animate(Animation::RUNNING_RED_BLUE, 5000, [
    'speed' => 50,
    'fade_factor' => 0.85
]);
```

**Options:**
- `speed` (int): ms per pixel (default: `50`)
- `fade_factor` (float): Trail fade (default: `0.85`)

**Use Cases:** Dual motion, police lights effect, opposing motion

---

### RUNNING_RANDOM
**Running dot that changes colors**

```php
$strip->animate(Animation::RUNNING_RANDOM, 5000, [
    'speed' => 50,
    'fade_factor' => 0.85,
    'color_change_interval' => 5  // Change every 5 pixels
]);
```

**Options:**
- `speed` (int): ms per pixel (default: `50`)
- `fade_factor` (float): Trail fade (default: `0.85`)
- `color_change_interval` (int): Pixels before color change (default: `5`)

**Use Cases:** Rainbow trails, random racing, colorful motion

---

### LARSON_SCANNER
**KITT/Cylon eye scanner effect** (Better than SCAN)

```php
$strip->animate(Animation::LARSON_SCANNER, 5000, [
    'color' => 0xFF0000,
    'speed' => 30,
    'eye_size' => 3,
    'fade_factor' => 0.75
]);
```

**Options:**
- `color` (int): Eye color (default: `0xFF0000`)
- `speed` (int): ms per pixel (default: `30`)
- `eye_size` (int): Width of eye (default: `3`)
- `fade_factor` (float): Trail fade (default: `0.75`)

**Use Cases:** Classic KITT effect, retro scanners, Cylon eye

---

### CHASE_FLASH
**Theater chase with periodic full flash**

```php
$strip->animate(Animation::CHASE_FLASH, 5000, [
    'color' => 0xFF0000,
    'speed_ms' => 100,
    'group_size' => 3,
    'flash_interval' => 10,  // Flash every 10 steps
    'flash_color' => 0xFFFFFF
]);
```

**Options:**
- `color` (int): Chase color (default: `0xFF0000`)
- `speed_ms` (int): Chase speed (default: `100`)
- `group_size` (int): Pixel grouping (default: `3`)
- `flash_interval` (int): Steps between flashes (default: `10`)
- `flash_color` (int): Flash color (default: `0xFFFFFF`)

**Use Cases:** Dramatic chase effects, pulsing patterns

---

### CHASE_RAINBOW_WHITE
**Rainbow chase with white gaps**

```php
$strip->animate(Animation::CHASE_RAINBOW_WHITE, 5000, [
    'speed_ms' => 100,
    'group_size' => 3
]);
```

**Options:**
- `speed_ms` (int): Chase speed (default: `100`)
- `group_size` (int): Pixel grouping (default: `3`)

**Use Cases:** Colorful marquees, rainbow chase with contrast

---

### CHASE_BLACKOUT
**Chase with blackout between steps**

```php
$strip->animate(Animation::CHASE_BLACKOUT, 5000, [
    'color' => 0xFF0000,
    'speed_ms' => 150,
    'group_size' => 3,
    'blackout_ms' => 75
]);
```

**Options:**
- `color` (int): Chase color (default: `0xFF0000`)
- `speed_ms` (int): Chase speed (default: `150`)
- `group_size` (int): Pixel grouping (default: `3`)
- `blackout_ms` (int): Blackout duration (default: `75`)

**Use Cases:** Dramatic effects, on/off chase patterns

---

### CHASE_BLACKOUT_RAINBOW
**Rainbow chase with blackouts**

```php
$strip->animate(Animation::CHASE_BLACKOUT_RAINBOW, 5000, [
    'speed_ms' => 150,
    'group_size' => 3,
    'blackout_ms' => 75
]);
```

**Options:**
- `speed_ms` (int): Chase speed (default: `150`)
- `group_size` (int): Pixel grouping (default: `3`)
- `blackout_ms` (int): Blackout duration (default: `75`)

**Use Cases:** Rainbow marquee with drama, colorful pulsing

---

## Fire & Heat Effects

### FIRE_FLICKER
**Realistic fire simulation**

```php
$strip->animate(Animation::FIRE_FLICKER, 10000, [
    'intensity' => 0.8,  // Brightness
    'speed' => 30,
    'cooling' => 55  // Heat dissipation
]);
```

**Options:**
- `intensity` (float): Brightness 0-1 (default: `0.8`)
- `speed` (int): Update speed in ms (default: `30`)
- `cooling` (int): Heat dissipation rate (default: `55`)

**Use Cases:** Fire simulation, torch effects, flame displays

**Algorithm:** Uses heat map with cooling, diffusion, and random ignition at base

---

## Comet & Meteor Effects

### COMET
**Comet with trailing tail**

```php
$strip->animate(Animation::COMET, 5000, [
    'color' => 0xFFFFFF,
    'speed' => 30,
    'tail_length' => 10,
    'reverse' => false
]);
```

**Options:**
- `color` (int): Comet color (default: `0xFFFFFF`)
- `speed` (int): ms per pixel (default: `30`)
- `tail_length` (int): Tail length in pixels (default: `10`)
- `reverse` (bool): Reverse direction (default: `false`)

**Use Cases:** Shooting stars, space effects, directional motion

---

### METEOR_SHOWER
**Multiple meteors with trails**

```php
$strip->animate(Animation::METEOR_SHOWER, 10000, [
    'color' => 0xFFFFFF,
    'meteor_size' => 5,
    'trail_decay' => 128,
    'random_decay' => true,
    'speed_delay' => 30
]);
```

**Options:**
- `color` (int): Meteor color (default: `0xFFFFFF`)
- `meteor_size` (int): Meteor length (default: `5`)
- `trail_decay` (int): Fade amount per frame (default: `128`)
- `random_decay` (bool): Random trail decay (default: `true`)
- `speed_delay` (int): ms between frames (default: `30`)

**Use Cases:** Space themes, meteor displays, dynamic effects

---

## Fireworks & Explosions

### FIREWORKS
**Random explosion bursts**

```php
$strip->animate(Animation::FIREWORKS, 10000, [
    'frequency' => 0.05,  // Chance per frame
    'fade_speed' => 0.85,
    'explosion_size' => 5
]);
```

**Options:**
- `frequency` (float): Probability 0-1 (default: `0.05`)
- `fade_speed` (float): Fade factor (default: `0.85`)
- `explosion_size` (int): Burst radius (default: `5`)

**Use Cases:** Celebration effects, random bursts, firework displays

---

## Wave & Motion Effects

### SAW
**Sawtooth brightness wave**

```php
$strip->animate(Animation::SAW, 5000, [
    'color' => 0xFF0000,
    'cycle_ms' => 1000,
    'reverse' => false
]);
```

**Options:**
- `color` (int): Base color (default: `0xFF0000`)
- `cycle_ms` (int): Cycle duration (default: `1000`)
- `reverse` (bool): Ramp down instead of up (default: `false`)

**Use Cases:** Linear brightness transitions, sawtooth patterns

---

### RAIN
**Rain drops falling**

```php
$strip->animate(Animation::RAIN, 10000, [
    'color' => 0x0000FF,
    'intensity' => 0.1,  // Drop frequency
    'speed' => 50,
    'fade_factor' => 0.7
]);
```

**Options:**
- `color` (int): Rain color (default: `0x0000FF`)
- `intensity` (float): Drop frequency 0-1 (default: `0.1`)
- `speed` (int): Fall speed ms/pixel (default: `50`)
- `fade_factor` (float): Trail fade (default: `0.7`)

**Use Cases:** Weather effects, water simulation, rain displays

---

## Physics & Simulation

### DISSOLVE
**Random pixel transitions between colors**

```php
$strip->animate(Animation::DISSOLVE, 10000, [
    'colors' => [0xFF0000, 0x00FF00, 0x0000FF],
    'transition_ms' => 2000
]);
```

**Options:**
- `colors` (array): Color palette (default: `[0xFF0000, 0x00FF00, 0x0000FF]`)
- `transition_ms` (int): Transition time (default: `2000`)

**Use Cases:** Organic transitions, pixel-by-pixel color changes

---

### DISSOLVE_RANDOM
**Dissolve with random colors**

```php
$strip->animate(Animation::DISSOLVE_RANDOM, 10000, [
    'transition_ms' => 2000
]);
```

**Options:**
- `transition_ms` (int): Transition time (default: `2000`)

**Use Cases:** Unpredictable color changes, random transitions

---

## Gradient & Blend Effects

### GRADIENT
**Smooth flowing gradient**

```php
$strip->animate(Animation::GRADIENT, 10000, [
    'colors' => [0xFF0000, 0x00FF00, 0x0000FF],
    'speed' => 50,
    'spread' => 3.0  // Gradient stretch
]);
```

**Options:**
- `colors` (array): Gradient colors (default: `[0xFF0000, 0x00FF00, 0x0000FF]`)
- `speed` (int): Movement speed (default: `50`)
- `spread` (float): Gradient scale (default: `3.0`)

**Use Cases:** Flowing colors, smooth transitions, color waves

---

## Usage Tips

### Chaining Animations

```php
// Play multiple animations in sequence
$strip->animate(Animation::WIPE, 2000, ['color' => 0xFF0000]);
$strip->animate(Animation::SPARKLE, 3000);
$strip->animate(Animation::FADE, 2000);
```

### With Laravel (using LaravelRGB)

```php
use DeptOfScrapyardRobotics\LaravelRGB\Support\Facades\LightingSetup;
use PhpdaFruit\NeoPixels\Enums\Animation;

$strip = LightingSetup::getPixelChannel('my_strip');
$strip->animate(Animation::RAINBOW, 5000);
```

### Via Artisan Command

```bash
# Play comet animation on all devices for 10 seconds
php artisan rgb:animate comet all --duration=10000

# Play fire forever on strip (Ctrl+C to stop)
php artisan rgb:animate fire-flicker strip
```

### Animation Performance

- **Light**: STATIC, BLINK, BREATHE, FADE
- **Medium**: RAINBOW, THEATER_CHASE, SCAN, WIPE
- **Heavy**: FIRE_FLICKER, METEOR_SHOWER, FIREWORKS, GRADIENT

For Raspberry Pi / Jetson devices, all animations run smoothly. Adjust `speed` options if needed.

---

## Custom Options

Every animation accepts custom options via the third parameter:

```php
$strip->animate(Animation::COMET, 5000, [
    'color' => 0x00FFFF,     // Custom cyan comet
    'speed' => 20,           // Faster than default
    'tail_length' => 15,     // Longer tail
    'reverse' => true        // Go backwards
]);
```

Check each animation's default options to see what can be customized!

---

## Creating Custom Animations

See the main README for information on creating custom animation visualizations by implementing the `AnimationVisualization` interface.

