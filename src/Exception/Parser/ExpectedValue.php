<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedValue extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected value - either literal or variable reference, got "%s".';
}
