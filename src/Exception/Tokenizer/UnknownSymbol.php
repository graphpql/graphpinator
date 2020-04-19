<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class UnknownSymbol extends TokenizerError
{
    public const MESSAGE = 'Unknown symbol.';
}
