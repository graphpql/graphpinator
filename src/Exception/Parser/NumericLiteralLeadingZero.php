<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class NumericLiteralLeadingZero extends TokenizerError
{
    public const MESSAGE = 'Numeric literal with leading zeroes.';
}
