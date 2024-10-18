<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceContractMissingField extends TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - missing field "%s".';

    public function __construct(
        string $childName,
        string $interfaceName,
        string $fieldName,
    )
    {
        parent::__construct([$childName, $interfaceName, $fieldName]);
    }
}
