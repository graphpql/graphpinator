<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class AtLeastOneConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'AtLeastOne constraint was not satisfied.';
}
