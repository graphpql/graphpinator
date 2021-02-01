<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MaxSizeConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Max size constraint was not satisfied.';
}
