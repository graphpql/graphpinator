<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class UnknownSymbol extends \Graphpinator\Exception\Tokenizer\TokenizerError
{
    public const MESSAGE = 'Unknown symbol.';
}
