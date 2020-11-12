<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

abstract class ArgumentFieldConstraint implements \Graphpinator\Constraint\Constraint
{
    use \Nette\SmartObject;

    abstract public function print() : string;

    abstract public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool;

    public function validate(\Graphpinator\Value\Value $value) : void
    {
        if ($value instanceof \Graphpinator\Value\NullValue) {
            return;
        }

        $this->validateFactoryMethod($value->getRawValue());
    }

    public function isContravariant(\Graphpinator\Constraint\ArgumentFieldConstraint $childConstraint) : bool
    {
        return $this->isGreaterSet($childConstraint, $this);
    }

    public function isCovariant(\Graphpinator\Constraint\ArgumentFieldConstraint $childConstraint) : bool
    {
        return $this->isGreaterSet($this, $childConstraint);
    }

    abstract protected function validateFactoryMethod($rawValue) : void;

    abstract protected function isGreaterSet(self $greater, self $smaller) : bool;

    protected static function validateOneOf(array $greater, array $smaller) : bool
    {
        foreach ($smaller as $value) {
            if (!\in_array($value, $greater, true)) {
                return false;
            }
        }

        return true;
    }
}
