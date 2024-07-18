<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceContractMissingArgument extends TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - argument "%s" on field "%s" is missing.';

    public function __construct(string $childName, string $interfaceName, string $fieldName, string $argumentName)
    {
        parent::__construct([$childName, $interfaceName, $argumentName, $fieldName]);
    }
}
