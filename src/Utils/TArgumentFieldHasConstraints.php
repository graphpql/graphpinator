<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

trait TArgumentFieldHasConstraints
{
    use \Graphpinator\Utils\THasConstraints;

    public function addConstraint(\Graphpinator\Constraint\ArgumentFieldConstraint $constraint) : self
    {
        if (!$constraint->validateType($this->getType())) {
            throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
        }

        $this->getConstraints()[] = $constraint;

        return $this;
    }
}
