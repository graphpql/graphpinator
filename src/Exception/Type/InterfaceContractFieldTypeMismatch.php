<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractFieldTypeMismatch extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type doesnt satisfy interface - field type does not match';
}
