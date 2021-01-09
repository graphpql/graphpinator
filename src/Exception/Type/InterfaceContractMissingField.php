<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractMissingField extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type "%s" doesnt satisfy interface "%s" - missing field "%s".';

    public function __construct(string $childName, string $interfaceName, string $fieldName)
    {
        $this->messageArgs = [$childName, $interfaceName, $fieldName];

        parent::__construct();
    }
}
