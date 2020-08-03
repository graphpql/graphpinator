<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Argument;

final class ArgumentNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Field is not defined.';
}
