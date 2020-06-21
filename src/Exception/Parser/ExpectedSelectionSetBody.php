<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedSelectionSetBody extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Expected field, fragment spread or closing brace.';
}
