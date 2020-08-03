<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class OperationWithoutName extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Multiple operations given, but not all have specified name.';
}
