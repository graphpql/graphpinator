<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class NumericLiteralFollowedByName extends Tokenizer
{
    public const MESSAGE = 'Numeric literal cannot be followed by name.';
}
