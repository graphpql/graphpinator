<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

/**
 * @method \Graphpinator\Constraint\Constraint current() : object
 * @method \Graphpinator\Constraint\Constraint offsetGet($offset) : object
 */
final class ConstraintSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Constraint::class;

    public function validate(\Graphpinator\Value\Value $value) : void
    {
        foreach ($this as $constraint) {
            $constraint->validate($value);
        }
    }

    public function isConvariant(self $compare) : bool
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

    public function validateObjectConstraint(self $compare) : bool
    {
        if (\count($compare) === 0) {
            return true;
        }

        foreach ($this as $constraintContract) {
            \assert($constraintContract instanceof \Graphpinator\Constraint\ObjectConstraint);

            foreach ($compare as $constraint) {
                \assert($constraint instanceof \Graphpinator\Constraint\ObjectConstraint);

                if (!$constraintContract->validateConstraint($constraint)) {
                    return false;
                }
            }
        }

        return true;
    }
}
