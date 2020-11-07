<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedFieldName extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected field name, got "%s".';
}
