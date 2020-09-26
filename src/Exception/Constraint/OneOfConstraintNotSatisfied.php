<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class OneOfConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'OneOf constraint was not satisfied.';
}
