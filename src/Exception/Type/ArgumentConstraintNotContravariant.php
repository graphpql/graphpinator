<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ArgumentConstraintNotContravariant extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - argument "%s" on field "%s" has constraint which is not contravariant.';

    public function __construct(string $childName, string $interfaceName, string $fieldName, string $argumentName)
    {
        $this->messageArgs = [$childName, $interfaceName, $argumentName, $fieldName];

        parent::__construct();
    }
}
