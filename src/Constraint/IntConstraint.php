<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class IntConstraint
{
    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        if (\is_int($greater->min) && ($smaller->min === null || $smaller->min < $greater->min)) {
            return false;
        }

        if (\is_int($greater->max) && ($smaller->max === null || $smaller->max > $greater->max)) {
            return false;
        }

        return !\is_array($greater->oneOf) || ($smaller->oneOf !== null && self::validateOneOf($greater->oneOf, $smaller->oneOf));
    }
}
