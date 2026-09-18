<?php

namespace GeneralPurposeIO\Contracts\IntegratedCircuits;

/**
 * The bus channels #[IntegratedCircuit] and #[Pinout] name, and the factory
 * parameter prefix each one contributes. SPI chips carry a second channel for
 * their DC and reset lines, so those two get their own prefixes; everything
 * else is the plain adapter and device.
 */
enum CircuitTransport: string
{
    case SPI = 'SPI';
    case I2C = 'I2C';
    case UART = 'UART';
    case DIGITAL_IO = 'DigitalIO';
    case GPIO = 'GPIO';
    case PWM = 'PWM';

    public static function tryFromLabel(string $label): ?self
    {
        return match (strtolower(str_replace(['_', '-'], '', $label))) {
            'spi' => self::SPI,
            'i2c' => self::I2C,
            'uart' => self::UART,
            'digitalio', 'digital' => self::DIGITAL_IO,
            'gpio' => self::GPIO,
            'pwm' => self::PWM,
            default => null,
        };
    }

    public function adapterParam(): string
    {
        return match ($this) {
            self::SPI => 'spi_adapter',
            self::DIGITAL_IO => 'digital_adapter',
            default => 'adapter',
        };
    }

    public function deviceParam(): string
    {
        return match ($this) {
            self::SPI => 'spi_device',
            self::DIGITAL_IO => 'digital_device',
            default => 'device',
        };
    }
}
