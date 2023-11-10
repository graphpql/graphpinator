<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceContractArgumentTypeMismatch extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - argument "%s" on field "%s" does not have a compatible type.';

    public function __construct(string $childName, string $interfaceName, string $fieldName, string $argumentName)
    {
        parent::__construct([$childName, $interfaceName, $argumentName, $fieldName]);
    }
}
