<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class InputValue implements \Graphpinator\Value\InputedValue, \IteratorAggregate
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Type\InputType $type,
        private \stdClass $value,
    ) {}

    public function getRawValue(bool $forResolvers = false) : object
    {
        $return = $forResolvers === true
            ? new ($this->getType()->getDataClass())
            : new \stdClass();

        foreach ((array) $this->value as $fieldName => $fieldValue) {
            \assert($fieldValue instanceof \Graphpinator\Value\ArgumentValue);

            $return->{$fieldName} = $fieldValue->getValue()->getRawValue($forResolvers);
        }

        return $return;
    }

    public function getType() : \Graphpinator\Type\InputType
    {
        return $this->type;
    }

    public function printValue() : string
    {
        $component = [];

        foreach ((array) $this->value as $key => $value) {
            \assert($value instanceof \Graphpinator\Value\ArgumentValue);

            $component[] = $key . ':' . $value->getValue()->printValue();
        }

        return '{' . \implode(',', $component) . '}';
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        foreach ($this->value as $key => $value) {
            \assert($value instanceof \Graphpinator\Value\ArgumentValue);

            $value->applyVariables($variables);
        }

        foreach ($this->type->getDirectiveUsages() as $directive) {
            $directive->getDirective()->resolveInputObject($directive->getArgumentValues(), $this);
        }
    }

    public function isSame(Value $compare) : bool
    {
        if (!$compare instanceof self) {
            return false;
        }

        $secondObject = $compare->value;

        if (\count((array) $secondObject) !== \count((array) $this->value)) {
            return false;
        }

        foreach ($this->value as $key => $value) {
            \assert($value instanceof \Graphpinator\Value\ArgumentValue);

            if (!\property_exists($secondObject, $key) || !$value->getValue()->isSame($secondObject->{$key}->getValue())) {
                return false;
            }
        }

        return true;
    }
    
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->value);
    }

    public function __isset(string $name) : bool
    {
        return \property_exists($this->value, $name);
    }

    public function __get(string $name) : \Graphpinator\Value\ArgumentValue
    {
        return $this->value->{$name};
    }

    public function __set(string $name, \Graphpinator\Value\ArgumentValue $value) : void
    {
        if ($value->getArgument() !== $this->type->getArguments()[$name]) {
            throw new \Exception();
        }

        $this->value->{$name} = $value;
    }
}
