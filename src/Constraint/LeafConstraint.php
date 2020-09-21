<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

abstract class LeafConstraint extends \Graphpinator\Constraint\ArgumentConstraint
{
    public function validate(\Graphpinator\Resolver\Value\ValidatedValue $inputValue) : void
    {
        switch (\get_class($inputValue)) {
            case \Graphpinator\Resolver\Value\NullValue::class:
                return;
            case \Graphpinator\Resolver\Value\ListValue::class:
                foreach ($inputValue as $value) {
                    $this->validate($value);
                }

                return;
            default:
                $this->validateFactoryMethod($inputValue->getRawValue());
        }
    }
}
