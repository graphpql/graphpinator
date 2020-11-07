<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedRoot extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected operation or fragment definition, got "%s".';
}
