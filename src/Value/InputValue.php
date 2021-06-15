<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class InputValue implements \Graphpinator\Value\InputedValue, \IteratorAggregate
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Typesystem\InputType $type,
        private \stdClass $value,
    )
    {
    }

    public function getRawValue(bool $forResolvers = false) : object
    {
        $return = $forResolvers === true
            ? new ($this->getType()->getDataClass())
            : new \stdClass();

        foreach ((array) $this->value as $argumentName => $argumentValue) {
            \assert($argumentValue instanceof \Graphpinator\Value\ArgumentValue);

            $return->{$argumentName} = $argumentValue->getValue()->getRawValue($forResolvers);
        }

        return $return;
    }

    public function getType() : \Graphpinator\Typesystem\InputType
    {
        return $this->type;
    }

    public function printValue() : string
    {
        $component = [];

        foreach ((array) $this->value as $argumentName => $argumentValue) {
            \assert($argumentValue instanceof \Graphpinator\Value\ArgumentValue);

            $component[] = $argumentName . ':' . $argumentValue->getValue()->printValue();
        }

        return '{' . \implode(',', $component) . '}';
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        foreach ((array) $this->value as $argumentValue) {
            \assert($argumentValue instanceof \Graphpinator\Value\ArgumentValue);

            $argumentValue->applyVariables($variables);
        }

        foreach ($this->type->getDirectiveUsages() as $directive) {
            $directive->getDirective()->resolveInputObject($directive->getArgumentValues(), $this);
        }
    }

    public function resolveRemainingDirectives() : void
    {
        foreach ((array) $this->value as $argumentValue) {
            \assert($argumentValue instanceof \Graphpinator\Value\ArgumentValue);

            $argumentValue->resolveNonPureDirectives();
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

        foreach ((array) $this->value as $argumentName => $argumentValue) {
            \assert($argumentValue instanceof \Graphpinator\Value\ArgumentValue);

            if (!\property_exists($secondObject, $argumentName) ||
                !$argumentValue->getValue()->isSame($secondObject->{$argumentName}->getValue())) {
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
