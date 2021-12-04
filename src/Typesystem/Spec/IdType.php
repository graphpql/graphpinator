<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

#[\Graphpinator\Typesystem\Attribute\Description('ID built-in type')]
final class IdType extends \Graphpinator\Typesystem\ScalarType
{
    protected const NAME = 'ID';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue);
    }

    public function coerceValue(mixed $rawValue) : mixed
    {
        return \is_int($rawValue)
            ? (string) $rawValue
            : $rawValue;
    }
}
