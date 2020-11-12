<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

/**
 * Trait THasConstraints which manages constraints for classes which support it.
 */
trait THasConstraints
{
    private ?\Graphpinator\Constraint\ConstraintSet $constraints = null;

    public function validateConstraints(\Graphpinator\Value\Value $value) : void
    {
        $this->getConstraints()->validate($value);
    }

    public function printConstraints() : string
    {
        $return = '';

        foreach ($this->getConstraints() as $constraint) {
            $return .= ' ' . $constraint->print();
        }

        return $return;
    }
}
