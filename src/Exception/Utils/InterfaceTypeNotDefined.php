<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Utils;

final class InterfaceTypeNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'InterfaceType is not defined.';
}
