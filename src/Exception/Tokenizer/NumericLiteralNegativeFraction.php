<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class NumericLiteralNegativeFraction extends \Graphpinator\Exception\Tokenizer\TokenizerError
{
    public const MESSAGE = 'Negative fraction part in numeric value.';
}
