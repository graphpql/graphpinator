<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MinLengthConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Min length constraint was not satisfied.';
}
