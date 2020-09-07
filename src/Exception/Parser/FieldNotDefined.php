<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class FieldNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Field is not defined.';
}
