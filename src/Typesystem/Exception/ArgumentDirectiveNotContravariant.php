<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class ArgumentDirectiveNotContravariant extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - argument "%s" on field "%s" has directive which is not contravariant (%s).';

    public function __construct(string $childName, string $interfaceName, string $fieldName, string $argumentName, string $message)
    {
        parent::__construct([$childName, $interfaceName, $argumentName, $fieldName, $message]);
    }
}
