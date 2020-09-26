<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MaxItemsConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Max items constraint was not satisfied.';
}
