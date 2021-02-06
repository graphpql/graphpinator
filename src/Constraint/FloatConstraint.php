<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class FloatConstraint
{
    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        if (\is_float($greater->min) && ($smaller->min === null || $smaller->min < $greater->min)) {
            return false;
        }

        if (\is_float($greater->max) && ($smaller->max === null || $smaller->max > $greater->max)) {
            return false;
        }

        return !\is_array($greater->oneOf) || ($smaller->oneOf !== null && self::validateOneOf($greater->oneOf, $smaller->oneOf));
    }
}
