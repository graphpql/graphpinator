<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Scalar;

final class FloatType extends ScalarType
{
    protected const NAME = 'Float';
    protected const DESCRIPTION = 'Float built-in type';

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_float($rawValue)) {
            return;
        }

        throw new \Exception();
    }
}
