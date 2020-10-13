<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

trait TObjectConstraint
{
    use \Graphpinator\Utils\THasConstraints;

    public function addConstraint(\Graphpinator\Constraint\ObjectConstraint $constraint) : self
    {
        if (!$constraint->validateType($this)) {
            throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
        }

        $this->getConstraints()[] = $constraint;

        return $this;
    }
}
