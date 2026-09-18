<?php

namespace GeneralPurposeIO\Contracts\IntegratedCircuits;

/** Names the framework owns, so a delegating chip package never guesses them. */
enum CircuitConsoleCommand: string
{
    case MAKE_PROFILE = 'circuit:make-profile';
    case PUBLISH_CONFIG_TAG = 'gpio-circuits-config';
}
