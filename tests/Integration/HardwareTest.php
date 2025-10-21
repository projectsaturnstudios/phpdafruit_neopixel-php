<?php

use PhpdaFruit\NeoPixels\PixelBus;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

/**
 * Integration tests for actual hardware
 * 
 * These tests require:
 * - phpixel extension installed
 * - Physical hardware connected (LED strips/fans)
 * - SPI devices available at /dev/spidevX.Y
 * 
 * Run with: ./vendor/bin/pest --testsuite=Integration
 */

describe('Hardware Integration', function () {
    
    beforeEach(function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
    });
    
    it('can initialize and control a single LED strip', function () {
        $strip = new PixelChannel(
            num_pixels: 15,
            device_path: SPIDevice::SPI_0_0->value,
            neopixel_type: NeoPixelType::RGB
        );
        
        // Red flash
        $strip->fill(0xFF0000);
        $strip->show();
        usleep(500000); // 0.5s
        
        // Clear
        $strip->clear();
        $strip->show();
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow');
    
    it('can control multiple devices via PixelBus', function () {
        $bus = new PixelBus([
            'strip' => new PixelChannel(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'fan' => new PixelChannel(2, SPIDevice::SPI_1_0->value, NeoPixelType::GRB),
        ]);
        
        // Synchronized green flash
        $bus->useSource('strip', 'fill', [0x00FF00]);
        $bus->useSource('fan', 'fill', [0x00FF00]);
        $bus->useSource('strip', 'show', []);
        $bus->useSource('fan', 'show', []);
        
        usleep(500000);
        
        // Clear both
        $bus->useSource('strip', 'clear', []);
        $bus->useSource('fan', 'clear', []);
        $bus->useSource('strip', 'show', []);
        $bus->useSource('fan', 'show', []);
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow');
    
    it('can handle rapid color changes', function () {
        $strip = new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        $colors = [0xFF0000, 0x00FF00, 0x0000FF, 0xFFFF00, 0xFF00FF, 0x00FFFF];
        
        foreach ($colors as $color) {
            $strip->fill($color);
            $strip->show();
            usleep(100000); // 100ms per color
        }
        
        $strip->clear();
        $strip->show();
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow');
    
    it('can demonstrate brightness fading', function () {
        $strip = new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        $strip->fill(0xFFFFFF); // White
        
        // Fade up
        for ($brightness = 0; $brightness <= 255; $brightness += 25) {
            $strip->setBrightness($brightness);
            $strip->show();
            usleep(50000);
        }
        
        // Fade down
        for ($brightness = 255; $brightness >= 0; $brightness -= 25) {
            $strip->setBrightness($brightness);
            $strip->show();
            usleep(50000);
        }
        
        $strip->clear();
        $strip->show();
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow');
    
    it('can create a chase effect', function () {
        $strip = new PixelChannel(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        for ($i = 0; $i < 15; $i++) {
            $strip->clear();
            $strip->setPixelColor($i, 0, 255, 255); // Cyan
            $strip->show();
            usleep(50000);
        }
        
        $strip->clear();
        $strip->show();
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow');
    
    it('can demonstrate independent brightness control across channels', function () {
        $bus = new PixelBus([
            'bright' => new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'dim' => new PixelChannel(10, SPIDevice::SPI_1_0->value, NeoPixelType::RGB),
        ]);
        
        // Both red
        $bus->useSource('bright', 'fill', [0xFF0000]);
        $bus->useSource('dim', 'fill', [0xFF0000]);
        
        // Different brightness
        $bus->useSource('bright', 'setBrightness', [255]);
        $bus->useSource('dim', 'setBrightness', [64]);
        
        $bus->useSource('bright', 'show', []);
        $bus->useSource('dim', 'show', []);
        
        usleep(1000000); // 1s
        
        // Clear
        $bus->useSource('bright', 'clear', []);
        $bus->useSource('dim', 'clear', []);
        $bus->useSource('bright', 'show', []);
        $bus->useSource('dim', 'show', []);
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow');
    
    it('can demonstrate rainbow effect', function () {
        $strip = new PixelChannel(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        $rainbow = [
            0xFF0000, // Red
            0xFF7F00, // Orange
            0xFFFF00, // Yellow
            0x00FF00, // Green
            0x0000FF, // Blue
            0x4B0082, // Indigo
            0x9400D3, // Violet
        ];
        
        // Create scrolling rainbow
        for ($offset = 0; $offset < 20; $offset++) {
            for ($i = 0; $i < 15; $i++) {
                $colorIdx = ($i + $offset) % count($rainbow);
                $color = $rainbow[$colorIdx];
                $r = ($color >> 16) & 0xFF;
                $g = ($color >> 8) & 0xFF;
                $b = $color & 0xFF;
                $strip->setPixelColor($i, $r, $g, $b);
            }
            $strip->show();
            usleep(100000);
        }
        
        $strip->clear();
        $strip->show();
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow', 'visual');
    
    it('verifies GRB vs RGB pixel type handling', function () {
        // Test with RGB order
        $rgb_strip = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        $rgb_strip->fill(0xFF0000); // Should show as RED
        $rgb_strip->show();
        usleep(500000);
        $rgb_strip->clear();
        $rgb_strip->show();
        
        // Test with GRB order
        $grb_strip = new PixelChannel(5, SPIDevice::SPI_1_0->value, NeoPixelType::GRB);
        $grb_strip->fill(0xFF0000); // Should show as RED (library handles conversion)
        $grb_strip->show();
        usleep(500000);
        $grb_strip->clear();
        $grb_strip->show();
        
        expect(true)->toBeTrue();
    })->group('hardware', 'slow', 'visual');
});

