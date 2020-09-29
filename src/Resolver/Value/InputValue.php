<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

final class InputValue extends \Graphpinator\Resolver\Value\ValidatedValue implements \ArrayAccess
{
    public function __construct(\stdClass $fields, \Graphpinator\Type\InputType $type)
    {
        foreach ($fields as $name => $temp) {
            if (isset($type->getArguments()[$name])) {
                continue;
            }

            throw new \Exception('Unknown field for input value');
        }

        $value = new \stdClass();

        foreach ($type->getArguments() as $argument) {
            $usedValue = $fields->{$argument->getName()}
                ?? $argument->getDefaultValue();

            // default values are already validated
            $value->{$argument->getName()} = $usedValue instanceof \Graphpinator\Resolver\Value\ValidatedValue
                ? $usedValue
                : $argument->getType()->createValue($usedValue);

            $argument->validateConstraints($value->{$argument->getName()});
        }

        parent::__construct($value, $type);

        $type->validateConstraints($this);
    }

    public function getRawValue() : \stdClass
    {
        $return = new \stdClass();

        foreach ($this->value as $name => $listItem) {
            \assert($listItem instanceof ValidatedValue);

            $return->{$name} = $listItem->getRawValue();
        }

        return $return;
    }

    public function printValue(bool $prettyPrint = false) : string
    {
        $component = [];

        foreach ($this->value as $key => $value) {
            \assert($value instanceof ValidatedValue);

            $component[$key] = $key . ($prettyPrint ? ': ' : ':') . $value->printValue($prettyPrint);
        }

        if (!$component) {
            return '{}';
        }

        return $prettyPrint
            ? '{' . \PHP_EOL . "\x20\x20\x20\x20" . \implode(',' . \PHP_EOL . "\x20\x20\x20\x20", $component) . \PHP_EOL . "\x20\x20" . '}'
            : '{' . \implode(',', $component) . '}';
    }

    public function offsetExists($name) : bool
    {
        return \property_exists($this->value, $name);
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return $this->value->{$offset};
    }

    public function offsetSet($offset, $value) : void
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function offsetUnset($offset) : void
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }
}
