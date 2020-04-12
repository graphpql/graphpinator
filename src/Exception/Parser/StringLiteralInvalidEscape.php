<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class StringLiteralInvalidEscape extends ParseError
{
    public const MESSAGE = 'String literal with invalid escape sequence.';
}
