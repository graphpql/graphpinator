<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedType extends ParserError
{
    public const MESSAGE = 'Expected type reference.';
}
