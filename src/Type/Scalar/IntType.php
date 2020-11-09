<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

final class IntType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Int';
    protected const DESCRIPTION = 'Int built-in type (32 bit)';
    private const INT_LIMIT = 2 ** 31;

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_int($rawValue)
            && $rawValue >= (- self::INT_LIMIT)
            && $rawValue <= self::INT_LIMIT;
    }
}
