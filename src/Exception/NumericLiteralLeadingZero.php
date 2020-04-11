<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class NumericLiteralLeadingZero extends Tokenizer
{
    public const MESSAGE = 'Numeric literal with leading zeroes.';
}
