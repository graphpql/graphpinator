<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractFieldTypeMismatch extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - field "%s" does not have a compatible type.';

    public function __construct(string $childName, string $interfaceName, string $fieldName)
    {
        $this->messageArgs = [$childName, $interfaceName, $fieldName];

        parent::__construct();
    }
}
