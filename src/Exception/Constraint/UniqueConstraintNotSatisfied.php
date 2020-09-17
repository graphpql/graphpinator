<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class UniqueConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Unique constraint was not satisfied.';
}
