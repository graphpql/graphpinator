<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractNewArgumentWithoutDefault extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - additional argument "%s" on field "%s" cannot be null.';

    public function __construct(string $childName, string $interfaceName, string $argumentName, string $fieldName)
    {
        $this->messageArgs = [$childName, $interfaceName, $argumentName, $fieldName];

        parent::__construct();
    }
}
