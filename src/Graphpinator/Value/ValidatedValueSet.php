<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Value;

final class ValidatedValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(GivenValueSet $givenValueSet, \Infinityloop\Graphpinator\Argument\ArgumentSet $argumentSet)
    {
        foreach ($argumentSet as $argument) {
            if (isset($givenValueSet[$argument->getName()])) {
                $givenValue = $givenValueSet[$argument->getName()];
                $this->appendUnique($argument->getName(), $argument->getType()->createValue($givenValue->getValue()));

                continue;
            }

            if ($argument->getDefaultValue() instanceof ValidatedValue) {
                $this->appendUnique($argument->getName(), $argument->getDefaultValue());

                continue;
            }

            $this->appendUnique($argument->getName(), new NullValue($argument->getType()));
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
