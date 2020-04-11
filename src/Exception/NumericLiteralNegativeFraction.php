<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class NumericLiteralNegativeFraction extends Tokenizer
{
    public const MESSAGE = 'Negative fraction part in numeric value.';
}
