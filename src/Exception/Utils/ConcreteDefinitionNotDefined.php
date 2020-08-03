<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Utils;

final class ConcreteDefinitionNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'ConcreteDefinition is not defined.';
}
