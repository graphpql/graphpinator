<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

#[Description('ID built-in type')]
final class IdType extends ScalarType
{
    protected const NAME = 'ID';

    #[\Override]
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue);
    }

    #[\Override]
    public function coerceValue(mixed $rawValue) : mixed
    {
        return \is_int($rawValue)
            ? (string) $rawValue
            : $rawValue;
    }
}
