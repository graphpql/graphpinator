<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedSelectionSet extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected selection set, got "%s".';
}
