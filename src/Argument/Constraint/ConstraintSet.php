<?php

declare(strict_types = 1);

namespace Graphpinator\Argument\Constraint;

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
}
