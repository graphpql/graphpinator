<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class StringLiteralWithoutEnd extends TokenizerError
{
    public const MESSAGE = 'String literal without proper end.';
}
