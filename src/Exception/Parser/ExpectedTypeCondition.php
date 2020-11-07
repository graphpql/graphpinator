<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedTypeCondition extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected type condition for fragment, got "%s".';
}
