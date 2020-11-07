<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedType extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected type reference, got "%s".';
}
