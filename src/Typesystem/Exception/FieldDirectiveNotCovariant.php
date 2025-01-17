<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class FieldDirectiveNotCovariant extends TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - field "%s" has directive which is not covariant (%s).';

    public function __construct(
        string $childName,
        string $interfaceName,
        string $fieldName,
        string $message,
    )
    {
        parent::__construct([$childName, $interfaceName, $fieldName, $message]);
    }
}
