<?php

namespace GeneralPurposeIO\Contracts\IntegratedCircuits\Attributes;

use GeneralPurposeIO\Contracts\IntegratedCircuits\CircuitTransport;

/**
 * What each channel of an option needs wired, index-aligned with
 * {@see IntegratedCircuit}'s options. Keys are channels, values are roles:
 *
 *   device        the bus device id (the adapter is always asked for too)
 *   chip_select   SPI chip select
 *   slave         I2C address
 *   anything else a GPIO line, asked for as {role}_pin — dc becomes dc_pin
 *
 *   #[Pinout(['I2C' => ['device', 'slave']], ['SPI' => ['device', 'chip_select'], 'DigitalIO' => ['dc', 'rst']])]
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Pinout
{
    /** @var list<array<string, string|list<string>>> */
    public array $pinout;

    /** @param array<string, string|list<string>> ...$pinout */
    public function __construct(array ...$pinout)
    {
        $this->pinout = $pinout;
    }

    /** @return array<string, string|list<string>>|null */
    public function forOptionIndex(int $index): ?array
    {
        return $this->pinout[$index] ?? null;
    }

    /** @return list<array{transport: ?CircuitTransport, label: string, roles: list<string>}> */
    public function channels(int $option_index): array
    {
        $map = $this->forOptionIndex($option_index);

        if (is_null($map)) {
            return [];
        }

        $channels = [];

        foreach ($map as $protocol => $roles) {
            $list = is_array($roles) ? array_values(array_map('strval', $roles)) : [(string) $roles];

            $channels[] = [
                'transport' => CircuitTransport::tryFromLabel((string) $protocol),
                'label' => (string) $protocol,
                'roles' => $list === [] ? ['device'] : $list,
            ];
        }

        return $channels;
    }

    /** @return list<string> */
    public function hintLines(int $option_index): array
    {
        return array_map(
            fn (array $channel): string => $channel['label'].': '.implode(', ', $channel['roles']),
            $this->channels($option_index),
        );
    }
}
