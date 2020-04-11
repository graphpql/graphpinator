<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class StringLiteralInvalidEscape extends Tokenizer
{
    public const MESSAGE = 'String literal with invalid escape sequence.';
}
