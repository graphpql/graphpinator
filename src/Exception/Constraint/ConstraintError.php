<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

abstract class ConstraintError extends \Graphpinator\Exception\GraphpinatorBase
{
    protected function isOutputable() : bool
    {
        return true;
    }
}
