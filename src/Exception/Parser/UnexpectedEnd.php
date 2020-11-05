<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class UnexpectedEnd extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Unexpected end of input. Probably missing closing brace?';
}
