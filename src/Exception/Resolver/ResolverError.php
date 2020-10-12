<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

abstract class ResolverError extends \Graphpinator\Exception\GraphpinatorBase
{
    protected function isOutputable() : bool
    {
        return true;
    }
}
