<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class StringLiteralInvalidEscape extends \Graphpinator\Exception\Tokenizer\TokenizerError
{
    public const MESSAGE = 'String literal with invalid escape sequence.';
}
