<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

use Graphpinator\Exception\GraphpinatorBase;

abstract class RequestError extends GraphpinatorBase
{
    public function isOutputable() : bool
    {
        return true;
    }
}
