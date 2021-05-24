<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Spec;

final class FloatType extends \Graphpinator\Type\ScalarType
{
    protected const NAME = 'Float';
    protected const DESCRIPTION = 'Float built-in type';

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
