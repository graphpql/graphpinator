<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class ArgumentInvalidTypeUsage extends TypeError
{
    public const MESSAGE = 'Argument %s is created with not-inputable type "%s", but fields can only contain inputable types.';

    public function __construct(
        string $argumentName,
        string $typeName,
    )
    {
        parent::__construct([$argumentName, $typeName]);
    }
}
