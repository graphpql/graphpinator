<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

trait TObjectConstraint
{
    use \Graphpinator\Utils\THasConstraints;

    public function addConstraint(\Graphpinator\Constraint\ObjectConstraint $constraint) : self
    {
        $this->getConstraints()[] = $constraint;

        if (!$constraint->validateType($this)) {
            throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
        }

        return $this;
    }

    public function getConstraints() : \Graphpinator\Constraint\ConstraintSet
    {
        if (!$this->constraints instanceof \Graphpinator\Constraint\ConstraintSet) {
            $this->constraints = new \Graphpinator\Constraint\ConstraintSet([]);
        }

        return $this->constraints;
    }
}
