<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

abstract class ParserError extends \Graphpinator\Exception\GraphpinatorBase
{
    public function __construct(\Graphpinator\Common\Location $location)
    {
        parent::__construct();

        $this->location = $location;
    }

    final protected function isOutputable() : bool
    {
        return true;
    }
}
