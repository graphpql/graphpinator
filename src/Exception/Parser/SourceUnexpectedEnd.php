<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class SourceUnexpectedEnd extends TokenizerError
{
    public const MESSAGE = 'Unexpected end of input.';
}
