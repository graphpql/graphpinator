<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class InputValue extends ValidatedValue implements \ArrayAccess
{
    public function __construct(array $fields, \PGQL\Type\InputType $type)
    {
        $value = [];

        foreach ($type->getArguments() as $argument) {
            $usedValue = $fields[$argument->getName()] ?? $argument->getDefaultValue();

            // default values are already validated
            $value[$argument->getName()] = $usedValue instanceof \PGQL\Value\ValidatedValue
                ? $usedValue
                : $argument->getType()->createValue($usedValue);
        }

        foreach ($fields as $name => $temp) {
            if (isset($type->getArguments()[$name])) {
                continue;
            }

            throw new \Exception('Unknown field for input value');
        }

        parent::__construct($value, $type);
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $name => $listItem) {
            \assert($listItem instanceof ValidatedValue);

            $return[$name] = $listItem->getRawValue();
        }

        return $return;
    }

    public function offsetExists($name) : bool
    {
        return \array_key_exists($name, $this->value);
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return $this->value[$offset];
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
