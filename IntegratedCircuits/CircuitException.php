<?php

namespace GeneralPurposeIO\Contracts\IntegratedCircuits;

use GeneralPurposeIO\Contracts\Core\GPIOLevelException;
use Throwable;

class CircuitException extends GPIOLevelException
{
    public static function notRegistered(string $slug): static
    {
        return new static("Circuit [{$slug}] is not registered.");
    }

    public static function noSuchProfile(string $name): static
    {
        return new static("Circuit profile [{$name}] is not defined in config/circuits.php.");
    }

    public static function profileNeeds(string $name, string $key): static
    {
        return new static("Circuit profile [{$name}] must define a non-empty [{$key}].");
    }

    public static function noSuchFactory(string $slug, string $class, string $protocol, ?Throwable $previous = null): static
    {
        return new static("Circuit [{$slug}] class [{$class}] has no static protocol factory [{$protocol}].", previous: $previous);
    }

    public static function factoryNotPublicStatic(string $slug, string $protocol): static
    {
        return new static("Circuit [{$slug}] protocol factory [{$protocol}] must be a public static method.");
    }

    public static function missingParameter(string $slug, string $protocol, string $parameter): static
    {
        return new static("Circuit [{$slug}] protocol [{$protocol}] is missing required parameter [{$parameter}].");
    }

    public static function factoryReturnedNonCircuit(string $slug, string $protocol): static
    {
        return new static("Circuit [{$slug}] protocol [{$protocol}] must return an IntegratedCircuit instance.");
    }

    public static function protocolRequired(string $slug): static
    {
        return new static("Circuit [{$slug}] needs a protocol() before make().");
    }

    public static function setterNeedsValue(string $name): static
    {
        return new static("Circuit fluent setter [{$name}] requires a value.");
    }

    // ---- scaffolding

    public static function noWiringDeclared(string $class): static
    {
        return new static("Class [{$class}] is missing #[IntegratedCircuit] wiring options.");
    }

    public static function noUsableWiring(string $class): static
    {
        return new static("Class [{$class}] declares no usable #[IntegratedCircuit] wiring options.");
    }

    public static function badProfileName(string $name): static
    {
        return new static("Profile name [{$name}] must be a simple identifier (letters, numbers, _ or -).");
    }

    public static function configNotPublished(string $path): static
    {
        return new static("Circuits config not found at [{$path}]. Publish it first: workshop vendor:publish --tag=gpio-circuits-config");
    }

    public static function configUnreadable(string $path): static
    {
        return new static("Unable to read circuits config at [{$path}].");
    }

    public static function configUnwritable(string $path): static
    {
        return new static("Unable to write circuits config at [{$path}].");
    }

    public static function profileExists(string $name, string $path): static
    {
        return new static("Circuit profile [{$name}] already exists in [{$path}].");
    }

    public static function configNotAnArray(): static
    {
        return new static('Circuits config does not look like a PHP array return (missing closing ];).');
    }
}
