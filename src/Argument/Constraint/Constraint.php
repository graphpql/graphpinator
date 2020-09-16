<?php

declare(strict_types = 1);

namespace Graphpinator\Argument\Constraint;

abstract class Constraint
{
    use \Nette\SmartObject;

    abstract public function printConstraint() : string;

    abstract public function validateType(\Graphpinator\Type\Contract\Inputable $type) : bool;

    public function validate(\Graphpinator\Resolver\Value\ValidatedValue $inputValue) : void
    {
        if ($inputValue instanceof \Graphpinator\Resolver\Value\NullValue) {
            return;
        }

        $this->validateFactoryMethod($inputValue->getRawValue());
    }

    abstract protected function validateFactoryMethod($rawValue) : void;
}
