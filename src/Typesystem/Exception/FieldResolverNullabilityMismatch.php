<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class FieldResolverNullabilityMismatch extends TypeError
{
    public const MESSAGE = 'Field %s has non-nullable type, but its resolver function return type allows null.';

    public function __construct(
        string $fieldName,
    )
    {
        parent::__construct([$fieldName]);
    }
}
