<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

/**
 * Trait THasConstraints which manages constraints for classes which support it.
 */
trait THasConstraints
{
    private ?\Graphpinator\Constraint\ConstraintSet $constraints = null;

    public function getConstraints() : \Graphpinator\Constraint\ConstraintSet
    {
        if (!$this->constraints instanceof \Graphpinator\Constraint\ConstraintSet) {
            $this->constraints = new \Graphpinator\Constraint\ConstraintSet([]);
        }

        return $this->constraints;
    }

    public function validateConstraints($rawValue) : void
    {
        $this->getConstraints()->validate($rawValue);
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
