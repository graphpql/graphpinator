<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

abstract class ArgumentConstraint implements \Graphpinator\Constraint\Constraint
{
    use \Nette\SmartObject;

    abstract public function print() : string;

    abstract public function validateType(\Graphpinator\Type\Contract\Inputable $type) : bool;

    public function validate(\Graphpinator\Resolver\Value\ValidatedValue $value) : void
    {
        if ($value instanceof \Graphpinator\Resolver\Value\NullValue) {
            return;
        }

        $this->validateFactoryMethod($value->getRawValue());
    }

    abstract protected function validateFactoryMethod($rawValue) : void;
}
