<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedClosingBracket extends ParserError
{
    public const MESSAGE = 'Expected closing ] for list type modifier.';
}
