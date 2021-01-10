<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractNewArgumentWithoutDefault extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - new argument "%s" on field "%s" does not have default value.';

    public function __construct(string $childName, string $interfaceName, string $fieldName, string $argumentName)
    {
        $this->messageArgs = [$childName, $interfaceName, $argumentName, $fieldName];

        parent::__construct();
    }
}
