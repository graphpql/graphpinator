<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceContractNewArgumentWithoutDefault extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - new argument "%s" on field "%s" does not have default value.';

    public function __construct(string $childName, string $interfaceName, string $fieldName, string $argumentName)
    {
        $this->messageArgs = [$childName, $interfaceName, $argumentName, $fieldName];

        parent::__construct();
    }
}
