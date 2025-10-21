<?php

use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

describe('PixelChannel', function () {
    
    it('can be instantiated with default values', function () {
        // Note: This test requires the phpixel extension to be installed
        // Skip if extension is not available
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        expect(function () {
            new PixelChannel();
        })->not->toThrow(Exception::class);
    });
    
    it('can be instantiated with custom parameters', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        expect(function () {
            new PixelChannel(
                num_pixels: 30,
                device_path: SPIDevice::SPI_1_0->value,
                neopixel_type: NeoPixelType::GRB
            );
        })->not->toThrow(Exception::class);
    });
    
    it('returns instance for fluent methods', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        expect($channel->updateLength(10))->toBeInstanceOf(PixelChannel::class)
            ->and($channel->updateType(NeoPixelType::GRB))->toBeInstanceOf(PixelChannel::class)
            ->and($channel->setDevicePath(SPIDevice::SPI_1_0->value))->toBeInstanceOf(PixelChannel::class);
    });
    
    it('implements WS821x contract', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel();
        
        expect($channel)->toBeInstanceOf(\PhpdaFruit\NeoPixels\Contracts\WS821x::class);
    });
    
    it('can set and get brightness', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        $channel->setBrightness(128);
        expect($channel->getBrightness())->toBe(128);
        
        $channel->setBrightness(255);
        expect($channel->getBrightness())->toBe(255);
        
        $channel->setBrightness(0);
        expect($channel->getBrightness())->toBe(0);
    });
    
    it('can set individual pixel colors', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Set pixel 0 to red
        $channel->setPixelColor(0, 255, 0, 0);
        
        // Set pixel 1 to green
        $channel->setPixelColor(1, 0, 255, 0);
        
        // Set pixel 2 to blue
        $channel->setPixelColor(2, 0, 0, 255);
        
        // Get the colors back (packed format 0xWWRRGGBB)
        expect($channel->getPixelColor(0))->toBe(0xFF0000); // Red
        expect($channel->getPixelColor(1))->toBe(0x00FF00); // Green
        expect($channel->getPixelColor(2))->toBe(0x0000FF); // Blue
    });
    
    it('can fill all pixels with a color', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Fill all pixels with cyan
        $channel->fill(0x00FFFF);
        
        // Check a few pixels
        expect($channel->getPixelColor(0))->toBe(0x00FFFF);
        expect($channel->getPixelColor(2))->toBe(0x00FFFF);
        expect($channel->getPixelColor(4))->toBe(0x00FFFF);
    });
    
    it('can fill a range of pixels', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Clear all first
        $channel->clear();
        
        // Fill pixels 2-4 with magenta
        $channel->fill(0xFF00FF, 2, 3);
        
        // Check the range
        expect($channel->getPixelColor(0))->toBe(0x000000); // Black (cleared)
        expect($channel->getPixelColor(1))->toBe(0x000000); // Black (cleared)
        expect($channel->getPixelColor(2))->toBe(0xFF00FF); // Magenta
        expect($channel->getPixelColor(3))->toBe(0xFF00FF); // Magenta
        expect($channel->getPixelColor(4))->toBe(0xFF00FF); // Magenta
        expect($channel->getPixelColor(5))->toBe(0x000000); // Black (cleared)
    });
    
    it('can clear all pixels', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Set some pixels to non-zero
        $channel->fill(0xFFFFFF);
        
        // Clear them
        $channel->clear();
        
        // Verify all are black
        expect($channel->getPixelColor(0))->toBe(0x000000);
        expect($channel->getPixelColor(2))->toBe(0x000000);
        expect($channel->getPixelColor(4))->toBe(0x000000);
    });
    
    it('handles RGBW pixel types', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGBW);
        
        // Set pixel with white component
        $channel->setPixelColor(0, 255, 128, 64, 200); // R, G, B, W
        
        // Note: The packed format should include the white component
        $color = $channel->getPixelColor(0);
        expect($color)->toBeGreaterThan(0);
    });
    
    it('can update pixel type dynamically', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Change to GRB
        $result = $channel->updateType(NeoPixelType::GRB);
        
        expect($result)->toBeInstanceOf(PixelChannel::class);
    });
    
    it('can update length dynamically', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Increase to 20 pixels
        $result = $channel->updateLength(20);
        
        expect($result)->toBeInstanceOf(PixelChannel::class);
    });
    
    it('can update device path dynamically', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Change device
        $result = $channel->setDevicePath(SPIDevice::SPI_1_0->value);
        
        expect($result)->toBeInstanceOf(PixelChannel::class);
    });
    
    it('allows method chaining', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        
        // Chain multiple operations
        $result = $channel
            ->updateLength(10)
            ->updateType(NeoPixelType::GRB)
            ->setDevicePath(SPIDevice::SPI_1_0->value);
        
        expect($result)->toBeInstanceOf(PixelChannel::class);
    });
});

