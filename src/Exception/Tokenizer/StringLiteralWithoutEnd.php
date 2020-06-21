<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class StringLiteralWithoutEnd extends \Graphpinator\Exception\Tokenizer\TokenizerError
{
    public const MESSAGE = 'String literal without proper end.';
}
