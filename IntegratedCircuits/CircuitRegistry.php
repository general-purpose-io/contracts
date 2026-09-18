<?php

namespace GeneralPurposeIO\Contracts\IntegratedCircuits;

/**
 * The chip catalog and build surface. A package registers the chip types it
 * ships; an app names a recipe in config/circuits.php; either way what comes
 * back is a live IntegratedCircuit.
 */
interface CircuitRegistry
{
    /** Catalog a chip type under a slug. A class that is not an IntegratedCircuit is ignored. */
    public function addCircuit(string $slug, string $class_name): void;

    public function has(string $slug): bool;

    /** @return array<string, class-string<IntegratedCircuit>> */
    public function listCircuits(): array;

    /** @return class-string<IntegratedCircuit> */
    public function resolveClass(string $slug): string;

    /** Build from a named recipe in config/circuits.php. */
    public function profile(string $name): IntegratedCircuit;

    /** @param array<string, mixed> $params */
    public function build(string $slug, string $protocol, array $params): IntegratedCircuit;

    /** A console command a package registered to scaffold profiles for this chip. */
    public function registerProfileCommand(string $slug, string $command): void;

    public function profileCommand(string $slug): ?string;
}
