<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class StringLiteralWithoutEnd extends Tokenizer
{
    public const MESSAGE = 'String literal without proper end.';
}
