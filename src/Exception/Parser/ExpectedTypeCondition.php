<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedTypeCondition extends ParserError
{
    public const MESSAGE = 'Expected type condition for fragment.';
}
