<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MinConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Min constraint was not satisfied.';
}
