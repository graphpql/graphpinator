<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

/**
 * @method \Graphpinator\Constraint\ArgumentFieldConstraint current() : object
 * @method \Graphpinator\Constraint\ArgumentFieldConstraint offsetGet($offset) : object
 */
final class ArgumentFieldConstraintSet extends \Graphpinator\Constraint\ConstraintSet
{
    protected const INNER_CLASS = ArgumentFieldConstraint::class;

    public function isContravariant(self $childSet) : bool
    {
        $index = 0;
        $constraintCount = \count($childSet);

        foreach ($this as $parentConstraint) {
            if ($index >= $constraintCount) {
                return true;
            }

            $childConstraint = $childSet[$index];

            if ($parentConstraint::class === $childConstraint::class && !$parentConstraint->isContravariant($childConstraint)) {
                return false;
            }

            $index++;
        }

        return $constraintCount <= $index;
    }

    public function isCovariant(self $childSet) : bool
    {
        $index = 0;
        $constraintCount = \count($this);

        foreach ($childSet as $childConstraint) {
            if ($index >= $constraintCount) {
                return true;
            }

            $parentConstraint = $this[$index];

            if ($parentConstraint::class === $childConstraint::class && !$parentConstraint->isCovariant($childConstraint)) {
                return false;
            }

            $index++;
        }

        return $constraintCount <= $index;
    }
}
