<?php

namespace GeneralPurposeIO\Contracts\IntegratedCircuits\Attributes;

use Attribute;

/**
 * How a chip can be wired. Each entry is one provisioning option: a string
 * for a single bus, a list for buses that come together.
 *
 *   #[IntegratedCircuit('I2C', ['SPI', 'DigitalIO'])]
 *
 * The static factory for an option is the first bus in it that is not a
 * digital-line channel, so ['SPI', 'DigitalIO'] calls spi().
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IntegratedCircuit
{
    /** @var list<string|list<string>> */
    public array $protocols;

    /** @param string|list<string> ...$protocols */
    public function __construct(string|array ...$protocols)
    {
        $this->protocols = $protocols;
    }

    /** @return list<array{label: string, protocols: list<string>, factory: string}> */
    public function options(): array
    {
        $options = [];

        foreach ($this->protocols as $entry) {
            $group = is_array($entry) ? array_values(array_map('strval', $entry)) : [(string) $entry];

            if ($group === []) {
                continue;
            }

            $options[] = [
                'label' => implode('+', $group),
                'protocols' => $group,
                'factory' => $this->factoryMethodName($group),
            ];
        }

        return $options;
    }

    /** @param list<string> $group */
    protected function factoryMethodName(array $group): string
    {
        foreach ($group as $protocol) {
            if (! in_array(strtolower($protocol), ['digitalio', 'digital_io', 'gpio'], true)) {
                return strtolower($protocol);
            }
        }

        return strtolower($group[0]);
    }
}
