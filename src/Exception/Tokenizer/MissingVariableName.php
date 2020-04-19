<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class MissingVariableName extends TokenizerError
{
    public const MESSAGE = 'Missing variable name after $ symbol.';
}
