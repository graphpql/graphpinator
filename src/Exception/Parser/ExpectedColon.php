<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedColon extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected colon, got "%s".';
}
