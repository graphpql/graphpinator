<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class FieldInvalidTypeUsage extends TypeError
{
    public const MESSAGE = 'Field %s is created with non-outputable type "%s", but fields can only contain outputable types.';

    public function __construct(
        string $fieldName,
        string $typeName,
    )
    {
        parent::__construct([$fieldName, $typeName]);
    }
}
