<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

abstract class ParserError extends \Graphpinator\Exception\GraphpinatorBase
{
    protected function isOutputable() : bool
    {
        return true;
    }
}
