<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractArgumentTypeMismatch extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type doesnt satisfy interface - argument type does not match';
}
