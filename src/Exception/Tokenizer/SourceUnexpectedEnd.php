<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class SourceUnexpectedEnd extends \Graphpinator\Exception\Tokenizer\TokenizerError
{
    public const MESSAGE = 'Unexpected end of input. Probably missing closing brace?';
}
