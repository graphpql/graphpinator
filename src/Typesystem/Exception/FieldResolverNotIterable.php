<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class FieldResolverNotIterable extends TypeError
{
    public const MESSAGE = 'Field %s has list type, but its resolver function return type is not iterable.';

    public function __construct(
        string $fieldName,
    )
    {
        parent::__construct([$fieldName]);
    }
}
