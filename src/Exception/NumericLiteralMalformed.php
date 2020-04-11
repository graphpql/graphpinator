<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class NumericLiteralMalformed extends Tokenizer
{
    public const MESSAGE = 'Numeric literal incorrectly formed.';
}
