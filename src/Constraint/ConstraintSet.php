<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

/**
 * @method Constraint current() : object
 * @method Constraint offsetGet($offset) : object
 */
final class ConstraintSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Constraint::class;

    public function validate(\Graphpinator\Value\Value $value) : void
    {
        foreach ($this as $constraint) {
            $constraint->validate($value);
        }
    }
}
