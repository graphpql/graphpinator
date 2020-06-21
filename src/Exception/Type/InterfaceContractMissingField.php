<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractMissingField extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type doesnt satisfy interface - missing field';
}
