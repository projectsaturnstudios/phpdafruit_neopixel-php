<?php

use PhpdaFruit\NeoPixels\PixelBus;
use PhpdaFruit\NeoPixels\PixelChannel;
use PhpdaFruit\NeoPixels\Enums\NeoPixelType;
use PhpdaFruit\NeoPixels\Enums\SPIDevice;

describe('PixelBus', function () {
    
    it('can be instantiated without channels', function () {
        $bus = new PixelBus();
        
        expect($bus)->toBeInstanceOf(PixelBus::class);
    });
    
    it('can be instantiated with a single channel', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        $bus = new PixelBus($channel);
        
        expect($bus)->toBeInstanceOf(PixelBus::class);
    });
    
    it('can be instantiated with multiple channels array', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channels = [
            'strip' => new PixelChannel(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'fan' => new PixelChannel(2, SPIDevice::SPI_1_0->value, NeoPixelType::GRB),
        ];
        
        $bus = new PixelBus($channels);
        
        expect($bus)->toBeInstanceOf(PixelBus::class);
    });
    
    it('can add a channel after instantiation', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus();
        $channel = new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::GRB);
        
        $result = $bus->addPixelChannel('led_strip', $channel);
        
        expect($result)->toBeInstanceOf(PixelBus::class);
    });
    
    it('allows method chaining when adding channels', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus();
        
        $result = $bus
            ->addPixelChannel('strip1', new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB))
            ->addPixelChannel('strip2', new PixelChannel(5, SPIDevice::SPI_1_0->value, NeoPixelType::GRB));
        
        expect($result)->toBeInstanceOf(PixelBus::class);
    });
    
    it('can control individual channels via useSource', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus();
        $channel = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        $bus->addPixelChannel('test_strip', $channel);
        
        // Set a pixel color via useSource
        expect(function () use ($bus) {
            $bus->useSource('test_strip', 'setPixelColor', [0, 255, 0, 0]);
        })->not->toThrow(Exception::class);
    });
    
    it('throws exception when accessing non-existent channel', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus();
        
        expect(function () use ($bus) {
            $bus->useSource('non_existent', 'clear', []);
        })->toThrow(Exception::class, 'Pixel channel non_existent not found');
    });
    
    it('can manage multiple independent channels', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus([
            'strip' => new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'fan' => new PixelChannel(2, SPIDevice::SPI_1_0->value, NeoPixelType::GRB),
        ]);
        
        // Control strip
        $bus->useSource('strip', 'fill', [0xFF0000]); // Red
        
        // Control fan independently
        $bus->useSource('fan', 'fill', [0x00FF00]); // Green
        
        // Both operations should work without throwing
        expect(true)->toBeTrue();
    });
    
    it('can call multiple methods on a channel', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus();
        $channel = new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        $bus->addPixelChannel('main', $channel);
        
        // Perform multiple operations
        $bus->useSource('main', 'clear', []);
        $bus->useSource('main', 'setBrightness', [128]);
        $bus->useSource('main', 'fill', [0x0000FF]);
        $bus->useSource('main', 'show', []);
        
        expect(true)->toBeTrue();
    });
    
    it('can handle different pixel types across channels', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus([
            'rgb_strip' => new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'grb_strip' => new PixelChannel(10, SPIDevice::SPI_1_0->value, NeoPixelType::GRB),
            'rgbw_strip' => new PixelChannel(5, SPIDevice::SPI_0_1->value, NeoPixelType::RGBW),
        ]);
        
        // Set colors on different types
        $bus->useSource('rgb_strip', 'fill', [0xFF0000]);
        $bus->useSource('grb_strip', 'fill', [0x00FF00]);
        $bus->useSource('rgbw_strip', 'fill', [0x0000FF]);
        
        expect(true)->toBeTrue();
    });
    
    it('can handle different device paths across channels', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus([
            'spi0_0' => new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'spi0_1' => new PixelChannel(10, SPIDevice::SPI_0_1->value, NeoPixelType::RGB),
            'spi1_0' => new PixelChannel(10, SPIDevice::SPI_1_0->value, NeoPixelType::RGB),
        ]);
        
        // Control different devices
        $bus->useSource('spi0_0', 'fill', [0xFF0000]);
        $bus->useSource('spi0_1', 'fill', [0x00FF00]);
        $bus->useSource('spi1_0', 'fill', [0x0000FF]);
        
        expect(true)->toBeTrue();
    });
    
    it('maintains channel independence for brightness', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $channel1 = new PixelChannel(5, SPIDevice::SPI_0_0->value, NeoPixelType::RGB);
        $channel2 = new PixelChannel(5, SPIDevice::SPI_1_0->value, NeoPixelType::RGB);
        
        $bus = new PixelBus([
            'bright' => $channel1,
            'dim' => $channel2,
        ]);
        
        // Set different brightness levels
        $bus->useSource('bright', 'setBrightness', [255]);
        $bus->useSource('dim', 'setBrightness', [64]);
        
        // Verify independence (through direct channel access)
        expect($channel1->getBrightness())->toBe(255);
        expect($channel2->getBrightness())->toBe(64);
    });
    
    it('can clear all channels individually', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        $bus = new PixelBus([
            'strip1' => new PixelChannel(10, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'strip2' => new PixelChannel(10, SPIDevice::SPI_1_0->value, NeoPixelType::RGB),
        ]);
        
        // Fill both
        $bus->useSource('strip1', 'fill', [0xFFFFFF]);
        $bus->useSource('strip2', 'fill', [0xFFFFFF]);
        
        // Clear both
        $bus->useSource('strip1', 'clear', []);
        $bus->useSource('strip2', 'clear', []);
        
        expect(true)->toBeTrue();
    });
    
    it('supports complex multi-channel orchestration', function () {
        if (!extension_loaded('phpixel')) {
            $this->markTestSkipped('phpixel extension not available');
        }
        
        // Simulate the dual device demo scenario
        $bus = new PixelBus([
            'strip' => new PixelChannel(15, SPIDevice::SPI_0_0->value, NeoPixelType::RGB),
            'fan' => new PixelChannel(2, SPIDevice::SPI_1_0->value, NeoPixelType::GRB),
        ]);
        
        // Synchronized color wave
        $colors = [0xFF0000, 0x00FF00, 0x0000FF];
        
        foreach ($colors as $color) {
            $bus->useSource('strip', 'fill', [$color]);
            $bus->useSource('fan', 'fill', [$color]);
            $bus->useSource('strip', 'show', []);
            $bus->useSource('fan', 'show', []);
        }
        
        expect(true)->toBeTrue();
    });
});

