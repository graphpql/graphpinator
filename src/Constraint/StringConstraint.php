<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class StringConstraint extends \Graphpinator\Constraint\LeafConstraint
{
    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        if (\is_int($greater->minLength) && ($smaller->minLength === null || $smaller->minLength < $greater->minLength)) {
            return false;
        }

        if (\is_int($greater->maxLength) && ($smaller->maxLength === null || $smaller->maxLength > $greater->maxLength)) {
            return false;
        }

        if (\is_string($greater->regex) && ($smaller->regex === null || $smaller->regex !== $greater->regex)) {
            return false;
        }

        return !\is_array($greater->oneOf) || ($smaller->oneOf !== null && self::validateOneOf($greater->oneOf, $smaller->oneOf));
    }
}
