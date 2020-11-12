<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

/**
 * @method \Graphpinator\Constraint\Constraint current() : object
 * @method \Graphpinator\Constraint\Constraint offsetGet($offset) : object
 */
class ConstraintSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Constraint::class;

    public function validate(\Graphpinator\Value\Value $value) : void
    {
        foreach ($this as $constraint) {
            $constraint->validate($value);
        }
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
