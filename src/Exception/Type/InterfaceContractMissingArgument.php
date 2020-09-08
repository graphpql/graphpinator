<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceContractMissingArgument extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Type doesnt satisfy interface - field is missing argument';
}
