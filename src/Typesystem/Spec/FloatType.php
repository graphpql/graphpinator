<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

#[\Graphpinator\Typesystem\Attribute\Description('Float built-in type')]
final class FloatType extends \Graphpinator\Typesystem\ScalarType
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
