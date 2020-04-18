<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedVariableName extends ParserError
{
    public const MESSAGE = 'Expected variable or closing parenthesis.';
}
