<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class NumericLiteralMalformed extends TokenizerError
{
    public const MESSAGE = 'Numeric literal incorrectly formed.';
}
