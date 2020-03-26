<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class ValidatedValueSet implements \ArrayAccess
{
    private array $values = [];

    public function __construct(GivenValueSet $givenValueSet, \PGQL\Argument\ArgumentSet $argumentSet)
    {
        foreach ($argumentSet as $argument) {
            if (isset($givenValueSet[$argument->getName()])) {
                $givenValue = $givenValueSet[$argument->getName()];
                $this->values[$argument->getName()] = $argument->getType()->createValue($givenValue->getValue());

                continue;
            }

            if ($argument->getDefaultValue() instanceof ValidatedValue) {
                $this->values[$argument->getName()] = $argument->getDefaultValue();

                continue;
            }

            $this->values[$argument->getName()] = $argument->getType()->createValue(null);
        }

        foreach ($givenValueSet as $givenValue) {
            $argumentSet[$givenValue->getName()];
        }
    }

    public function offsetExists($name) : bool
    {
        return \array_key_exists($name, $this->values);
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return $this->values[$offset];
    }

    public function offsetSet($offset, $value) : void
    {
        throw new \Exception();
    }

    public function offsetUnset($offset) : void
    {
        throw new \Exception();
    }
}
