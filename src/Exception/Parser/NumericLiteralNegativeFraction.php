<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class NumericLiteralNegativeFraction extends ParseError
{
    public const MESSAGE = 'Negative fraction part in numeric value.';
}
