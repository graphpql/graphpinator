<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

trait TFieldConstraint
{
    public function addConstraint(\Graphpinator\Constraint\ArgumentFieldConstraint $constraint) : self
    {
        if (!$constraint->validateType($this->getType())) {
            throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
        }

        $this->getConstraints()[] = $constraint;

        return $this;
    }

    public function getConstraints() : \Graphpinator\Constraint\ArgumentFieldConstraintSet
    {
        if (!$this->constraints instanceof \Graphpinator\Constraint\ArgumentFieldConstraintSet) {
            $this->constraints = new \Graphpinator\Constraint\ArgumentFieldConstraintSet([]);
        }

        return $this->constraints;
    }
}
