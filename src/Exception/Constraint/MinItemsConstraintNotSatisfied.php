<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MinItemsConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Min items constraint was not satisfied.';
}
