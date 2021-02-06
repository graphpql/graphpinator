<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ObjectConstraint implements \Graphpinator\Constraint\Constraint
{

    public function validateConstraint(\Graphpinator\Constraint\ObjectConstraint $childConstraint) : bool
    {
        return $this->atLeastOne === $childConstraint->atLeastOne
            && $this->exactlyOne === $childConstraint->exactlyOne;
    }
}
