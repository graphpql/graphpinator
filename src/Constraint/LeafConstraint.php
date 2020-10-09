<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

abstract class LeafConstraint extends \Graphpinator\Constraint\ArgumentFieldConstraint
{
    public function validate(\Graphpinator\Value\Value $value) : void
    {
        if ($value instanceof \Graphpinator\Value\ListValue) {
            foreach ($value as $item) {
                $this->validate($item);
            }

            return;
        }

        parent::validate($value);
    }
}
