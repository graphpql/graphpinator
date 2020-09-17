<?php

declare(strict_types = 1);

namespace Graphpinator\Argument\Constraint;

abstract class LeafConstraint extends Constraint
{
    public function validate(\Graphpinator\Resolver\Value\ValidatedValue $inputValue) : void
    {
        if ($inputValue instanceof \Graphpinator\Resolver\Value\NullValue) {
            return;
        }

        if ($inputValue instanceof \Graphpinator\Resolver\Value\LeafValue) {
            $this->validateFactoryMethod($inputValue->getRawValue());

            return;
        }

        if ($inputValue instanceof \Graphpinator\Resolver\Value\ListValue) {
            foreach ($inputValue as $value) {
                $this->validate($value);
            }
        }
    }
}
