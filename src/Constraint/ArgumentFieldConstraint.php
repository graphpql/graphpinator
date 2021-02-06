<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

abstract class ArgumentFieldConstraint
{
    use \Nette\SmartObject;

    public function isContravariant(\Graphpinator\Constraint\ArgumentFieldConstraint $childConstraint) : bool
    {
        return $this->isGreaterSet($childConstraint, $this);
    }

    public function isCovariant(\Graphpinator\Constraint\ArgumentFieldConstraint $childConstraint) : bool
    {
        return $this->isGreaterSet($this, $childConstraint);
    }

    abstract protected function validateFactoryMethod(\stdClass|array|string|int|float|bool|null $rawValue) : void;

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
