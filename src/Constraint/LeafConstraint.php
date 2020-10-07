<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

abstract class LeafConstraint extends \Graphpinator\Constraint\ArgumentConstraint
{
    public function validate(\Graphpinator\Value\InputableValue $value) : void
    {
        if ($value instanceof \Graphpinator\Value\InputableListValue) {
            foreach ($value as $item) {
                $this->validate($item);
            }

            return;
        }

        parent::validate($value);
    }
}
