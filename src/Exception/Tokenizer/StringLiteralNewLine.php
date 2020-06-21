<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class StringLiteralNewLine extends \Graphpinator\Exception\Tokenizer\TokenizerError
{
    public const MESSAGE = 'Simple string literal cannot span across multiple lines. Use block literal or escape sequence.';
}
