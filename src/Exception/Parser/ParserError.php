<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

abstract class ParserError extends \Graphpinator\Exception\GraphpinatorBase
{
    public function __construct(\Graphpinator\Source\Location $location)
    {
        parent::__construct($location);
    }

    final protected function isOutputable() : bool
    {
        return true;
    }
}
