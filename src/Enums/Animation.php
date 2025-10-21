<?php

namespace PhpdaFruit\NeoPixels\Enums;

/**
 * Animation Effects Enum
 *
 * Comprehensive list of LED animation effects inspired by WLED
 * Organized by category for easy discovery
 */
enum Animation: string
{
    // ========================================
    // BASIC EFFECTS (1-15) - ALL COMPLETE! ✅
    // ========================================
    case STATIC = 'static';                                 // ✅ Built
    case BLINK = 'blink';                                   // ✅ Built
    case BREATHE = 'breathe';                               // ✅ Built
    case WIPE = 'wipe';                                     // ✅ Built
    case WIPE_RANDOM = 'wipe_random';                       // ✅ Built
    case RANDOM_COLORS = 'random_colors';                   // ✅ Built
    case SWEEP = 'sweep';                                   // ✅ Built
    case DYNAMIC = 'dynamic';                               // ✅ Built
    case COLORLOOP = 'colorloop';                           // ✅ Built
    case RAINBOW = 'rainbow';                               // ✅ Built
    case SCAN = 'scan';                                     // ✅ Built
    case SCAN_DUAL = 'scan_dual';                           // ✅ Built
    case FADE = 'fade';                                     // ✅ Built
    case THEATER_CHASE = 'theater_chase';                   // ✅ Built
    case THEATER_CHASE_RAINBOW = 'theater_chase_rainbow';   // ✅ Built

    // ========================================
    // SPARKLE & TWINKLE EFFECTS (16-30)
    // ========================================
    case TWINKLE = 'twinkle';                               // ✅ Built
    case SPARKLE = 'sparkle';                               // ✅ Built
    case SPARKLE_DARK = 'sparkle_dark';                     // ✅ Built
    case SPARKLE_PLUS = 'sparkle_plus';                     // ✅ Built
    // case FLASH_SPARKLE = 'flash_sparkle';                // ❌ TODO
    // case HYPER_SPARKLE = 'hyper_sparkle';                // ❌ TODO
    case STROBE = 'strobe';                                 // ✅ Built
    // case STROBE_RAINBOW = 'strobe_rainbow';              // ❌ TODO
    // case MULTI_STROBE = 'multi_strobe';                  // ❌ TODO
    case BLINK_RAINBOW = 'blink_rainbow';                   // ✅ Built
    // case COLORTWINKLE = 'colortwinkle';                  // ❌ TODO
    // case TWINKLEFOX = 'twinklefox';                      // ❌ TODO
    // case TWINKLECAT = 'twinklecat';                      // ❌ TODO
    // case TWINKLEUP = 'twinkleup';                        // ❌ TODO
    // case GLITTER = 'glitter';                            // ❌ TODO
    // case SOLID_GLITTER = 'solid_glitter';                // ❌ TODO
    // case SPOTS = 'spots';                                // ❌ TODO
    // case SPOTS_FADE = 'spots_fade';                      // ❌ TODO

    // ========================================
    // CHASE & RUNNING EFFECTS (31-50)
    // ========================================
    case RUNNING = 'running';                               // ✅ Built
    case RUNNING_COLOR = 'running_color';                   // ✅ Built
    case RUNNING_RED_BLUE = 'running_red_blue';             // ✅ Built
    case RUNNING_RANDOM = 'running_random';                 // ✅ Built
    case LARSON_SCANNER = 'larson_scanner';                 // ✅ Built (KITT effect)
    case CHASE_FLASH = 'chase_flash';                       // ✅ Built
    case CHASE_RAINBOW_WHITE = 'chase_rainbow_white';       // ✅ Built
    case CHASE_BLACKOUT = 'chase_blackout';                 // ✅ Built
    case CHASE_BLACKOUT_RAINBOW = 'chase_blackout_rainbow'; // ✅ Built
    // case RUNNING_LIGHTS = 'running_lights';              // ❌ TODO
    // case CHASE_WHITE = 'chase_white';                    // ❌ TODO
    // case CHASE_COLOR = 'chase_color';                    // ❌ TODO
    // case CHASE_RANDOM = 'chase_random';                  // ❌ TODO
    // case CHASE_RAINBOW = 'chase_rainbow';                // ❌ TODO
    // case CHASE_FLASH_RANDOM = 'chase_flash_random';      // ❌ TODO
    // case RAINBOW_RUNNER = 'rainbow_runner';              // ❌ TODO
    // case COLOR_SWEEP_RANDOM = 'color_sweep_random';      // ❌ TODO
    // case TRAFFIC_LIGHT = 'traffic_light';                // ❌ TODO
    // case MERRY_CHRISTMAS = 'merry_christmas';            // ❌ TODO
    // case RAILWAY = 'railway';                            // ❌ TODO
    // case CANDY_CANE = 'candy_cane';                      // ❌ TODO
    // case COLORFUL = 'colorful';                          // ❌ TODO

    // ========================================
    // FIRE & HEAT EFFECTS (51-60)
    // ========================================
    case FIRE_FLICKER = 'fire_flicker';                     // ✅ Built
    // case FIRE = 'fire';                                  // ❌ TODO
    // case FIRE_2012 = 'fire_2012';                        // ❌ TODO
    // case CANDLE = 'candle';                              // ❌ TODO
    // case CANDLE_MULTI = 'candle_multi';                  // ❌ TODO
    // case NOISEFIRE = 'noisefire';                        // ❌ TODO

    // ========================================
    // COMET & METEOR EFFECTS (61-70)
    // ========================================
    case COMET = 'comet';                                   // ✅ Built
    case METEOR_SHOWER = 'meteor_shower';                   // ✅ Built
    // case METEOR = 'meteor';                              // ❌ TODO
    // case METEOR_SMOOTH = 'meteor_smooth';                // ❌ TODO

    // ========================================
    // FIREWORKS & EXPLOSIONS (71-80)
    // ========================================
    case FIREWORKS = 'fireworks';                           // ✅ Built
    // case FIREWORKS_RANDOM = 'fireworks_random';          // ❌ TODO
    // case FIREWORKS_STARBURST = 'fireworks_starburst';    // ❌ TODO
    // case FIREWORKS_1D = 'fireworks_1d';                  // ❌ TODO
    // case POPCORN = 'popcorn';                            // ❌ TODO

    // ========================================
    // WAVE & MOTION EFFECTS (81-100)
    // ========================================
    case SAW = 'saw';                                       // ✅ Built
    case RAIN = 'rain';                                     // ✅ Built
    // case WAVE = 'wave';                                  // ❌ TODO
    // case SINE = 'sine';                                  // ❌ TODO
    // case SINELON = 'sinelon';                            // ❌ TODO
    // case SINELON_DUAL = 'sinelon_dual';                  // ❌ TODO
    // case SINELON_RAINBOW = 'sinelon_rainbow';            // ❌ TODO
    // case PHASED = 'phased';                              // ❌ TODO
    // case PHASED_NOISE = 'phased_noise';                  // ❌ TODO
    // case WAVESINS = 'wavesins';                          // ❌ TODO
    // case FLOW = 'flow';                                  // ❌ TODO
    // case CHUNCHUN = 'chunchun';                          // ❌ TODO
    // case WASHING_MACHINE = 'washing_machine';            // ❌ TODO
    // case BLENDS = 'blends';                              // ❌ TODO
    // case COLORWAVES = 'colorwaves';                      // ❌ TODO

    // ========================================
    // WATER & LIQUID EFFECTS (101-110)
    // ========================================
    // case LAKE = 'lake';                                  // ❌ TODO
    // case RIPPLE = 'ripple';                              // ❌ TODO
    // case RIPPLE_RAINBOW = 'ripple_rainbow';              // ❌ TODO
    // case DRIP = 'drip';                                  // ❌ TODO
    // case PACIFICA = 'pacifica';                          // ❌ TODO
    // case PUDDLES = 'puddles';                            // ❌ TODO
    // case WATERFALL = 'waterfall';                        // ❌ TODO

    // ========================================
    // PLASMA & NOISE EFFECTS (111-120)
    // ========================================
    // case PLASMA = 'plasma';                              // ❌ TODO
    // case NOISE_PAL = 'noise_pal';                        // ❌ TODO
    // case NOISE = 'noise';                                // ❌ TODO
    // case FILL_NOISE = 'fill_noise';                      // ❌ TODO
    // case MIDNOISE = 'midnoise';                          // ❌ TODO

    // ========================================
    // PHYSICS & SIMULATION (121-130)
    // ========================================
    // case BOUNCING_BALLS = 'bouncing_balls';              // ❌ TODO
    // case JUGGLE = 'juggle';                              // ❌ TODO
    case DISSOLVE = 'dissolve';                             // ✅ Built
    case DISSOLVE_RANDOM = 'dissolve_random';               // ✅ Built
    // case BLACK_HOLE = 'black_hole';                      // ❌ TODO
    // case DANCING_SHADOWS = 'dancing_shadows';            // ❌ TODO

    // ========================================
    // GRADIENT & BLEND EFFECTS (131-140)
    // ========================================
    case GRADIENT = 'gradient';                             // ✅ Built
    // case GRADIENT_LINEAR = 'gradient_linear';            // ❌ TODO
    // case GRADIENT_RADIAL = 'gradient_radial';            // ❌ TODO
    // case CENTER_OUT = 'center_out';                      // ❌ TODO
    // case EDGES_IN = 'edges_in';                          // ❌ TODO

    // ========================================
    // SPECIAL PATTERNS (141-160)
    // ========================================
    // case HEARTBEAT = 'heartbeat';                        // ❌ TODO
    // case PERCENT = 'percent';                            // ❌ TODO
    // case SUNRISE = 'sunrise';                            // ❌ TODO
    // case TV_SIMULATOR = 'tv_simulator';                  // ❌ TODO
    // case DYNAMIC_SMOOTH = 'dynamic_smooth';              // ❌ TODO
    // case HALLOWEEN_EYES = 'halloween_eyes';              // ❌ TODO
    // case SOLID_PATTERN = 'solid_pattern';                // ❌ TODO
    // case SOLID_PATTERN_TRI = 'solid_pattern_tri';        // ❌ TODO
    // case ROCKTAVES = 'rocktaves';                        // ❌ TODO
    // case PRIDE = 'pride';                                // ❌ TODO
    // case PALETTE_CYCLE = 'palette_cycle';                // ❌ TODO

    // ========================================
    // 2D MATRIX EFFECTS (161-210)
    // Note: These require 2D LED matrices
    // ========================================
    // case MATRIX_2D = 'matrix_2d';                        // ❌ TODO (2D only)
    // case METABALLS_2D = 'metaballs_2d';                  // ❌ TODO (2D only)
    // case PULSER_2D = 'pulser_2d';                        // ❌ TODO (2D only)
    // case RIPPLES_2D = 'ripples_2d';                      // ❌ TODO (2D only)
    // case SWIRL_2D = 'swirl_2d';                          // ❌ TODO (2D only)
    // case WAVE_2D = 'wave_2d';                            // ❌ TODO (2D only)
    // case BLACK_HOLE_2D = 'black_hole_2d';                // ❌ TODO (2D only)
    // case DNA_2D = 'dna_2d';                              // ❌ TODO (2D only)
    // case DNA_SPIRAL_2D = 'dna_spiral_2d';                // ❌ TODO (2D only)
    // case DRIFT_2D = 'drift_2d';                          // ❌ TODO (2D only)
    // case FRIZZLES_2D = 'frizzles_2d';                    // ❌ TODO (2D only)
    // case LISSAJOUS_2D = 'lissajous_2d';                  // ❌ TODO (2D only)
    // case PLASMA_BALL_2D = 'plasma_ball_2d';              // ❌ TODO (2D only)
    // case POLAR_LIGHTS_2D = 'polar_lights_2d';            // ❌ TODO (2D only)
    // case SINDOTS_2D = 'sindots_2d';                      // ❌ TODO (2D only)
    // case SQUARED_SWIRL_2D = 'squared_swirl_2d';          // ❌ TODO (2D only)
    // case SUN_RADIATION_2D = 'sun_radiation_2d';          // ❌ TODO (2D only)
    // case TARTAN_2D = 'tartan_2d';                        // ❌ TODO (2D only)
    // case SPACESHIPS_2D = 'spaceships_2d';                // ❌ TODO (2D only)
    // case CRAZY_BEES_2D = 'crazy_bees_2d';                // ❌ TODO (2D only)
    // case GHOST_RIDER_2D = 'ghost_rider_2d';              // ❌ TODO (2D only)
    // case BLOBS_2D = 'blobs_2d';                          // ❌ TODO (2D only)
    // case SCROLL_2D = 'scroll_2d';                        // ❌ TODO (2D only)
    // case DRIFT_ROSE_2D = 'drift_rose_2d';                // ❌ TODO (2D only)
    // case DISTORTION_WAVES_2D = 'distortion_waves_2d';    // ❌ TODO (2D only)
    // case SOAP_2D = 'soap_2d';                            // ❌ TODO (2D only)
    // case OCTOPUS_2D = 'octopus_2d';                      // ❌ TODO (2D only)
    // case WAVING_CELL_2D = 'waving_cell_2d';              // ❌ TODO (2D only)
    // case PIXELS_2D = 'pixels_2d';                        // ❌ TODO (2D only)
    // case FIRE_2012_2D = 'fire_2012_2d';                  // ❌ TODO (2D only)
    // case DNA_2D_ALT = 'dna_2d_alt';                      // ❌ TODO (2D only)
    // case AKEMI_2D = 'akemi_2d';                          // ❌ TODO (2D only)
    // case GAME_OF_LIFE_2D = 'game_of_life_2d';            // ❌ TODO (2D only)
    // case MUNCH_2D = 'munch_2d';                          // ❌ TODO (2D only)
    // case NOISE_2D = 'noise_2d';                          // ❌ TODO (2D only)
    // case PLASMA_2D = 'plasma_2d';                        // ❌ TODO (2D only)
    // case RAINBOW_2D = 'rainbow_2d';                      // ❌ TODO (2D only)
    // case LAKE_2D = 'lake_2d';                            // ❌ TODO (2D only)
    // case WALTZ_2D = 'waltz_2d';                          // ❌ TODO (2D only)
    // case WAVERLY_2D = 'waverly_2d';                      // ❌ TODO (2D only)

    // ========================================
    // AUDIO REACTIVE EFFECTS (211-240)
    // Note: These require audio input/microphone
    // ========================================
    // case AUDIO_VOLUME = 'audio_volume';                  // ❌ TODO (Audio only)
    // case AUDIO_GRAVIMETER = 'audio_gravimeter';          // ❌ TODO (Audio only)
    // case AUDIO_JUGGLES = 'audio_juggles';                // ❌ TODO (Audio only)
    // case AUDIO_MATRIPIX = 'audio_matripix';              // ❌ TODO (Audio only)
    // case AUDIO_MIDNOISE = 'audio_midnoise';              // ❌ TODO (Audio only)
    // case AUDIO_NOISEMETER = 'audio_noisemeter';          // ❌ TODO (Audio only)
    // case AUDIO_PIXELS = 'audio_pixels';                  // ❌ TODO (Audio only)
    // case AUDIO_PLASMOID = 'audio_plasmoid';              // ❌ TODO (Audio only)
    // case AUDIO_PUDDLES = 'audio_puddles';                // ❌ TODO (Audio only)
    // case AUDIO_FREQMAP = 'audio_freqmap';                // ❌ TODO (Audio only)
    // case AUDIO_FREQMATRIX = 'audio_freqmatrix';          // ❌ TODO (Audio only)
    // case AUDIO_FREQPIXELS = 'audio_freqpixels';          // ❌ TODO (Audio only)
    // case AUDIO_FREQWAVE = 'audio_freqwave';              // ❌ TODO (Audio only)
    // case AUDIO_GRAVFREQ = 'audio_gravfreq';              // ❌ TODO (Audio only)
    // case AUDIO_NOISEFIRE = 'audio_noisefire';            // ❌ TODO (Audio only)
    // case AUDIO_PUDDLEPEAK = 'audio_puddlepeak';          // ❌ TODO (Audio only)
    // case AUDIO_RIPPLEBEAT = 'audio_ripplebeat';          // ❌ TODO (Audio only)
    // case AUDIO_WATERFALL = 'audio_waterfall';            // ❌ TODO (Audio only)

    /**
     * Get animation display name
     */
    public function getName(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }

    /**
     * Check if animation requires 2D matrix
     */
    public function requires2D(): bool
    {
        return str_ends_with($this->value, '_2d');
    }

    /**
     * Check if animation requires audio input
     */
    public function requiresAudio(): bool
    {
        return str_starts_with($this->value, 'audio_');
    }

    /**
     * Get animation category
     */
    public function getCategory(): string
    {
        return match (true) {
            in_array($this, [
                self::STATIC, self::BLINK, self::BREATHE, self::WIPE,
                self::WIPE_RANDOM, self::RANDOM_COLORS, self::SWEEP,
                self::DYNAMIC, self::COLORLOOP, self::RAINBOW, self::SCAN,
                self::SCAN_DUAL, self::FADE, self::THEATER_CHASE,
                self::THEATER_CHASE_RAINBOW
            ]) => 'Basic',

            in_array($this, [
                self::TWINKLE, self::SPARKLE, self::FLASH_SPARKLE,
                self::HYPER_SPARKLE, self::STROBE, self::STROBE_RAINBOW,
                self::MULTI_STROBE, self::BLINK_RAINBOW, self::COLORTWINKLE,
                self::TWINKLEFOX, self::TWINKLECAT, self::TWINKLEUP,
                self::GLITTER, self::SOLID_GLITTER, self::SPOTS, self::SPOTS_FADE
            ]) => 'Sparkle & Twinkle',

            in_array($this, [
                self::RUNNING_LIGHTS, self::CHASE_WHITE, self::CHASE_COLOR,
                self::CHASE_RANDOM, self::CHASE_RAINBOW, self::CHASE_FLASH,
                self::CHASE_FLASH_RANDOM, self::RAINBOW_RUNNER,
                self::COLOR_SWEEP_RANDOM, self::RUNNING_COLOR,
                self::RUNNING_RED_BLUE, self::RUNNING_RANDOM,
                self::LARSON_SCANNER, self::TRAFFIC_LIGHT,
                self::MERRY_CHRISTMAS, self::RAILWAY, self::CANDY_CANE,
                self::COLORFUL
            ]) => 'Chase & Running',

            in_array($this, [
                self::FIRE, self::FIRE_2012, self::FIRE_FLICKER,
                self::CANDLE, self::CANDLE_MULTI, self::NOISEFIRE
            ]) => 'Fire & Heat',

            in_array($this, [
                self::COMET, self::METEOR, self::METEOR_SMOOTH,
                self::METEOR_SHOWER
            ]) => 'Comet & Meteor',

            in_array($this, [
                self::FIREWORKS, self::FIREWORKS_RANDOM,
                self::FIREWORKS_STARBURST, self::FIREWORKS_1D, self::POPCORN
            ]) => 'Fireworks & Explosions',

            in_array($this, [
                self::WAVE, self::SAW, self::SINE, self::SINELON,
                self::SINELON_DUAL, self::SINELON_RAINBOW, self::PHASED,
                self::PHASED_NOISE, self::WAVESINS, self::FLOW,
                self::CHUNCHUN, self::WASHING_MACHINE, self::BLENDS,
                self::COLORWAVES
            ]) => 'Wave & Motion',

            in_array($this, [
                self::LAKE, self::RIPPLE, self::RIPPLE_RAINBOW,
                self::DRIP, self::PACIFICA, self::PUDDLES, self::WATERFALL
            ]) => 'Water & Liquid',

            in_array($this, [
                self::PLASMA, self::NOISE_PAL, self::NOISE,
                self::FILL_NOISE, self::MIDNOISE
            ]) => 'Plasma & Noise',

            in_array($this, [
                self::BOUNCING_BALLS, self::JUGGLE, self::DISSOLVE,
                self::DISSOLVE_RANDOM, self::BLACK_HOLE, self::DANCING_SHADOWS
            ]) => 'Physics & Simulation',

            in_array($this, [
                self::GRADIENT, self::GRADIENT_LINEAR, self::GRADIENT_RADIAL,
                self::CENTER_OUT, self::EDGES_IN
            ]) => 'Gradient & Blend',

            in_array($this, [
                self::HEARTBEAT, self::PERCENT, self::SUNRISE,
                self::TV_SIMULATOR, self::DYNAMIC_SMOOTH, self::HALLOWEEN_EYES,
                self::SOLID_PATTERN, self::SOLID_PATTERN_TRI, self::ROCKTAVES,
                self::PRIDE, self::PALETTE_CYCLE
            ]) => 'Special Patterns',

            $this->requires2D() => '2D Matrix',
            $this->requiresAudio() => 'Audio Reactive',
            default => 'Other'
        };
    }

    /**
     * Get all 1D compatible animations (no 2D or audio requirements)
     */
    public static function get1DAnimations(): array
    {
        return array_filter(
            self::cases(),
            fn($anim) => !$anim->requires2D() && !$anim->requiresAudio()
        );
    }

    /**
     * Get animations by category
     */
    public static function getByCategory(string $category): array
    {
        return array_filter(
            self::cases(),
            fn($anim) => $anim->getCategory() === $category
        );
    }
}
