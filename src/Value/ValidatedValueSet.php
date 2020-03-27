<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class ValidatedValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(GivenValueSet $givenValueSet, \PGQL\Argument\ArgumentSet $argumentSet)
    {
        foreach ($argumentSet as $argument) {
            if (isset($givenValueSet[$argument->getName()])) {
                $givenValue = $givenValueSet[$argument->getName()];
                $this->array[$argument->getName()] = $argument->getType()->createValue($givenValue->getValue());

                continue;
            }

            if ($argument->getDefaultValue() instanceof ValidatedValue) {
                $this->array[$argument->getName()] = $argument->getDefaultValue();

                continue;
            }

            $this->array[$argument->getName()] = $argument->getType()->createValue(null);
        }

        foreach ($givenValueSet as $givenValue) {
            $argumentSet[$givenValue->getName()];
        }
    }

    public function current() : ValidatedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return parent::offsetGet($offset);
    }
}
