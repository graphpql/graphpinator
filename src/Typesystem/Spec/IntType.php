<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

#[Description('Int built-in type (32 bit)')]
final class IntType extends ScalarType
{
    protected const NAME = 'Int';
    private const INT_LIMIT = 2 ** 31;

    #[\Override]
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_int($rawValue)
            && $rawValue >= (- self::INT_LIMIT)
            && $rawValue <= self::INT_LIMIT;
    }
}
