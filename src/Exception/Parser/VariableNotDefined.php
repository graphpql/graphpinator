<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class VariableNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Variable is not defined.';
}
