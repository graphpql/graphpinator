<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class ArgumentValueSet extends \Infinityloop\Utils\ObjectSet
{
    public function __construct(
        \Graphpinator\Parser\Value\NamedValueSet $namedValueSet,
        \Graphpinator\Argument\ArgumentSet $argumentSet
    )
    {
        foreach ($namedValueSet as $namedValue) {
            if (!$argumentSet->offsetExists($namedValue->getName())) {
                throw new \Graphpinator\Exception\Resolver\UnknownArgument();
            }
        }

        foreach ($argumentSet as $argument) {
            if ($namedValueSet->offsetExists($argument->getName())) {
                $this->appendUnique($argument, $argument->getType()->createValue($namedValueSet[$argument->getName()]->getRawValue()));

                continue;
            }

            if ($argument->getDefaultValue() instanceof \Graphpinator\Resolver\Value\ValidatedValue) {
                $this->appendUnique($argument, $argument->getDefaultValue());

                continue;
            }

            $this->appendUnique($argument, new \Graphpinator\Resolver\Value\NullValue($argument->getType()));
        }
    }

    public function current() : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return parent::offsetGet($offset);
    }

    public function getRawValues() : array
    {
        $return = [];

        foreach ($this as $argumentValue) {
            $return[] = $argumentValue->getRawValue();
        }

        return $return;
    }

    private function appendUnique(\Graphpinator\Argument\Argument $argument, \Graphpinator\Resolver\Value\ValidatedValue $value) : void
    {
        $argument->validateConstraints($value);

        if ($this->offsetExists($argument->getName())) {
            throw new \Exception('Duplicated item.');
        }

        $this->array[$argument->getName()] = $value;
    }
}
