<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedAfterOperationType extends ParserError
{
    public const MESSAGE = 'Expected operation name or selection set.';
}
