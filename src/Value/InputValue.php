<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class InputValue implements \Graphpinator\Value\InputedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\InputType $type;
    private \stdClass $value;

    private function __construct(\Graphpinator\Type\InputType $type, \stdClass $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public static function fromRaw(\Graphpinator\Type\InputType $type, \stdClass $rawValue) : self
    {
        $rawValue = self::mergeRaw($rawValue, (object) $type->getArguments()->getRawDefaults());

        foreach ((array) $rawValue as $name => $temp) {
            if ($type->getArguments()->offsetExists($name)) {
                continue;
            }

            throw new \Graphpinator\Exception\Normalizer\UnknownInputField($name, $type->getName());
        }

        $inner = new \stdClass();

        foreach ($type->getArguments() as $argument) {
            $inner->{$argument->getName()} = \Graphpinator\Value\ArgumentValue::fromRaw(
                $argument,
                $rawValue->{$argument->getName()}
                    ?? null,
            );
        }

        return new self($type, $inner);
    }

    public static function fromParsed(
        \Graphpinator\Type\InputType $type,
        \Graphpinator\Parser\Value\ObjectVal $parsed,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : self
    {
        foreach ($parsed->getValue() as $name => $temp) {
            if ($type->getArguments()->offsetExists($name)) {
                continue;
            }

            throw new \Graphpinator\Exception\Normalizer\UnknownInputField($name, $type->getName());
        }

        $inner = new \stdClass();

        foreach ($type->getArguments() as $argument) {
            if (!\property_exists($parsed->getValue(), $argument->getName())) {
                $inner->{$argument->getName()} = \Graphpinator\Value\ArgumentValue::fromRaw($argument, null);

                continue;
            }

            $inner->{$argument->getName()} = \Graphpinator\Value\ArgumentValue::fromParsed(
                $argument,
                $parsed->getValue()->{$argument->getName()},
                $variableSet,
            );
        }

        return new self($type, $inner);
    }

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
            $directive->getDirective()->resolveInputObject($this, $directive->getArgumentValues());
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

    private static function mergeRaw(\stdClass $core, \stdClass $supplement) : \stdClass
    {
        foreach ((array) $supplement as $key => $value) {
            if (\property_exists($core, $key)) {
                if ($core->{$key} instanceof \stdClass &&
                    $supplement->{$key} instanceof \stdClass) {
                    $core->{$key} = self::mergeRaw($core->{$key}, $supplement->{$key});
                }

                continue;
            }

            $core->{$key} = $value;
        }

        return $core;
    }
}
