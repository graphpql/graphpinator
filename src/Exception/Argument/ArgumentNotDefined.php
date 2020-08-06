<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Argument;

final class ArgumentNotDefined extends \Graphpinator\Exception\Argument\ArgumentError
{
    public const MESSAGE = 'Argument is not defined.';
}
