<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class NumericLiteralMalformed extends ParseError
{
    public const MESSAGE = 'Numeric literal incorrectly formed.';
}
