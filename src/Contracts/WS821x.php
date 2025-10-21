<?php

namespace PhpdaFruit\NeoPixels\Contracts;

use PhpdaFruit\NeoPixels\Enums\NeoPixelType;

interface WS821x
{
    public function begin(): void;

    public function show(): static;

    public function updateLength(int $n): static;

    public function updateType(NeoPixelType $type): static;

    public function setDevicePath(string $path): static;

    public function setPixelColor(int $n, int $r, int $g, int $b, int $w = 0): void;

    public function fill(int $color, int $first = 0, int $count = 0): static;

    public function  getPixelColor(int $n): int;

    public function setBrightness(int $b): void;

    public function getBrightness(): int;

    public function clear(): static;
}