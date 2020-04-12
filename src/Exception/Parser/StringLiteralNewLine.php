<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class StringLiteralNewLine extends ParseError
{
    public const MESSAGE = 'Simple string literal cannot span across multiple lines. Use block literal or escape sequence.';
}
