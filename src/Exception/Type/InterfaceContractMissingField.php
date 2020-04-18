<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractMissingField extends TypeError
{
    public const MESSAGE = 'Type doesnt satisfy interface - missing field';
}
