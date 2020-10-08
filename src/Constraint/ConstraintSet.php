<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ConstraintSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Constraint::class;

    public function current() : Constraint
    {
        return parent::current();
    }

    public function offsetGet($offset) : Constraint
    {
        return parent::offsetGet($offset);
    }

    public function validate(\Graphpinator\Value\Value $value) : void
    {
        foreach ($this as $constraint) {
            $constraint->validate($value);
        }
    }
}
