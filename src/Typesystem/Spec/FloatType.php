<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

#[Description('Float built-in type')]
final class FloatType extends ScalarType
{
    protected const NAME = 'Float';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_float($rawValue) && \is_finite($rawValue);
    }

    public function coerceValue(mixed $rawValue) : mixed
    {
        return \is_int($rawValue)
            ? (float) $rawValue
            : $rawValue;
    }
}
