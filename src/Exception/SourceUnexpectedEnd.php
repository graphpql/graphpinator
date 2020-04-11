<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class SourceUnexpectedEnd extends Tokenizer
{
    public const MESSAGE = 'Unexpected end of input.';
}
