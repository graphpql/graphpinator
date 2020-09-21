<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class ExactlyOneConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'ExactlyOne constraint was not satisfied.';
}
