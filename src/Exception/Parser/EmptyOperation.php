<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class EmptyOperation extends ParserError
{
    public const MESSAGE = 'No operation requested.';
}
