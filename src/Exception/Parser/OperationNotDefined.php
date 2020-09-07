<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class OperationNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Operation is not defined.';
}
