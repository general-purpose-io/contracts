<?php

namespace GeneralPurposeIO\Contracts\Digital;

use GeneralPurposeIO\Digital\DigitalEdgeEvent;

interface DigitalIODriver
{
    public function read(int $pin): bool;
    public function write(int $pin, bool $state): bool;
    public function listen(int $timeout, bool $rising_events, bool $falling_events, int $pin): ?DigitalEdgeEvent;
    public function close(): void;
}