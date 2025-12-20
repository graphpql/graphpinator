<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class FieldResolverVoidReturnType extends TypeError
{
    public const MESSAGE = 'Field %s resolver function cannot have void return type.';

    public function __construct(
        string $fieldName,
    )
    {
        parent::__construct([$fieldName]);
    }
}
