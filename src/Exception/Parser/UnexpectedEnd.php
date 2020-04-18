<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class UnexpectedEnd extends ParserError
{
    public const MESSAGE = 'Unexpected end of stream.';
}
