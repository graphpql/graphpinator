<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MaxConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Max constraint was not satisfied.';
}
