<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

final class IntType extends \Graphpinator\Typesystem\ScalarType
{
    protected const NAME = 'Int';
    protected const DESCRIPTION = 'Int built-in type (32 bit)';
    private const INT_LIMIT = 2 ** 31;

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_int($rawValue)
            && $rawValue >= (- self::INT_LIMIT)
            && $rawValue <= self::INT_LIMIT;
    }
}
