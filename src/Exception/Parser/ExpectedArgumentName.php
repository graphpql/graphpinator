<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedArgumentName extends ParserError
{
    public const MESSAGE = 'Expected argument or closing parenthesis.';
}
