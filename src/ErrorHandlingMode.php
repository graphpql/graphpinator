<?php

declare(strict_types = 1);

namespace Graphpinator;

enum ErrorHandlingMode
{
    // Catch all exceptions. ClientAware exceptions returns corresponding result, unknown exceptions throw an "Unknown error".
    case ALL;
    // Catch ClientAware exceptions and return corresponding result, unknown exceptions are rethrown.
    case CLIENT_AWARE;
    // Rethrow all exceptions.
    case NONE;

    public static function fromBool(bool $value) : self
    {
        return $value
            ? self::ALL
            : self::NONE;
    }
}
