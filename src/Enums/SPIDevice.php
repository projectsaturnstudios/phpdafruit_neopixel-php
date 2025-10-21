<?php

namespace PhpdaFruit\NeoPixels\Enums;

enum SPIDevice: string
{
    case SPI_0_0 = '/dev/spidev0.0';
    case SPI_0_1 = '/dev/spidev0.1';
    case SPI_1_0 = '/dev/spidev1.0';
    case SPI_1_1 = '/dev/spidev1.1';
    // SPI 2
    case SPI_2_0 = '/dev/spidev2.0';
    case SPI_2_1 = '/dev/spidev2.1';
    //SPI 3
    case SPI_3_0 = '/dev/spidev3.0';
    case SPI_3_1 = '/dev/spidev3.1';
    // SPI 4
    case SPI_4_0 = '/dev/spidev4.0';
    case SPI_4_1 = '/dev/spidev4.1';
}