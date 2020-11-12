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

    public function isCovariant(self $compare) : bool
    {
        if (\count($compare) === 0) {
            return true;
        }

        $index = 0;

        foreach ($this as $parentConstraint) {
            if ($index >= \count($compare)) {
                return true;
            }

            $childConstraint = $compare[$index];

            if (\get_class($parentConstraint) !== \get_class($childConstraint)) {
                $index++;

                continue;
            }

            if (!$parentConstraint->isCovariant($childConstraint)) {
                return false;
            }

            $index++;
        }

        return \count($compare) <= $index;
    }

    public function isContravariant(self $compare) : bool
    {
        if (\count($compare) === 0) {
            return true;
        }

        $index = 0;

        foreach ($this as $childConstraint) {
            if ($index >= \count($compare)) {
                return true;
            }

            $parentConstraint = $compare[$index];

            if (\get_class($childConstraint) !== \get_class($parentConstraint)) {
                $index++;

                continue;
            }

            if (!$parentConstraint->isContravariant($childConstraint)) {
                return false;
            }

            $index++;
        }

        return \count($compare) <= $index;
    }
}
