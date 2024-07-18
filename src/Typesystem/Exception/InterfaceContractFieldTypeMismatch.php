<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceContractFieldTypeMismatch extends TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - field "%s" does not have a compatible type.';

    public function __construct(string $childName, string $interfaceName, string $fieldName)
    {
        parent::__construct([$childName, $interfaceName, $fieldName]);
    }
}
