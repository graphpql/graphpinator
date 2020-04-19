<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class SourceUnexpectedEnd extends TokenizerError
{
    public const MESSAGE = 'Unexpected end of input.';
}
