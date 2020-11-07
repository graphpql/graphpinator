<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

/**
 * @method \Graphpinator\Constraint\Constraint current() : object
 * @method \Graphpinator\Constraint\Constraint offsetGet($offset) : object
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
