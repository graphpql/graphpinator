<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

/**
 * @extends ScalarType<int>
 */
#[Description('Int built-in type (32 bit)')]
final class IntType extends ScalarType
{
    protected const NAME = 'Int';
    // by specification there is a limit to 32 bits. ExtraTypes package contains a custom BigInt type.
    private const INT_LIMIT = 2 ** 31;

    #[\Override]
    public function validateAndCoerceInput(mixed $rawValue) : ?int
    {
        return \is_int($rawValue) && $rawValue >= (- self::INT_LIMIT) && $rawValue <= self::INT_LIMIT
            ? $rawValue
            : null;
    }

    #[\Override]
    public function coerceOutput(mixed $rawValue) : int
    {
        return $rawValue;
    }
}
