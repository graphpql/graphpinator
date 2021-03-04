<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

abstract class RequestError extends \Graphpinator\Exception\GraphpinatorBase
{
    protected function isOutputable() : bool
    {
        return true;
    }
}
