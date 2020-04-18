<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class UnknownSymbol extends ParseError
{
    public const MESSAGE = 'Unknown symbol.';
}
