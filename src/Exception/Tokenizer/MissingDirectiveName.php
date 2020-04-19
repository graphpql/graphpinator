<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class MissingDirectiveName extends TokenizerError
{
    public const MESSAGE = 'Missing directive name after @ symbol.';
}
