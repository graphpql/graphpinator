<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ListConstraint extends \Graphpinator\Constraint\ArgumentFieldConstraint
{
    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        return $this->recursiveValidateConstraints($greater->options, $smaller->options);
    }

    private function recursiveValidateConstraints(\stdClass $greater, \stdClass $smaller) : bool
    {
        if (\is_int($greater->minItems) && ($smaller->minItems === null || $smaller->minItems < $greater->minItems)) {
            return false;
        }

        if (\is_int($greater->maxItems) && ($smaller->maxItems === null || $smaller->maxItems > $greater->maxItems)) {
            return false;
        }

        if ($greater->unique === true && ($smaller->unique === null || $smaller->unique === false)) {
            return false;
        }

        return !($greater->innerList instanceof \stdClass)
            || ($smaller->innerList !== null && self::recursiveValidateConstraints($greater->innerList, $smaller->innerList));
    }
}
