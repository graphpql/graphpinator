<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedAfterOperationType extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected operation name or selection set, got "%s".';
}
