<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedLiteralValue extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected literal value as variable default value, got "%s".';
}
