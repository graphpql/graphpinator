<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class EmptyOperation extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'No operation requested.';
}
