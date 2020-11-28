<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class InputValue implements \Graphpinator\Value\InputedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\InputType $type;
    private \stdClass $value;

    public function __construct(\Graphpinator\Type\InputType $type, \stdClass $rawValue)
    {
        $rawValue = self::merge($rawValue, (object) $type->getArguments()->getRawDefaults());

        foreach ((array) $rawValue as $name => $temp) {
            if ($type->getArguments()->offsetExists($name)) {
                continue;
            }

            throw new \Exception('Unknown field for input value');
        }

        $value = new \stdClass();

        foreach ($type->getArguments() as $argument) {
            $value->{$argument->getName()} = new \Graphpinator\Normalizer\Value\ArgumentValue(
                $argument,
                $rawValue->{$argument->getName()}
                    ?? null,
            );
        }

        $this->type = $type;
        $this->value = $value;

        $type->validateConstraints($this);
    }

    public function getRawValue() : \stdClass
    {
        $return = new \stdClass();

        foreach ((array) $this->value as $fieldName => $fieldValue) {
            \assert($fieldValue instanceof \Graphpinator\Normalizer\Value\ArgumentValue);

            $return->{$fieldName} = $fieldValue->getValue()->getRawValue();
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
            \assert($value instanceof \Graphpinator\Normalizer\Value\ArgumentValue);

            $component[] = $key . ':' . $value->getValue()->printValue();
        }

        return '{' . \implode(',', $component) . '}';
    }

    public function prettyPrint(int $indentLevel) : string
    {
        if (\count((array) $this->value) === 0) {
            return '{}';
        }

        $component = [];
        $indent = \str_repeat('  ', $indentLevel);
        $innerIndent = $indent . '  ';

        foreach ((array) $this->value as $key => $value) {
            \assert($value instanceof \Graphpinator\Normalizer\Value\ArgumentValue);

            $component[] = $key . ': ' . $value->getValue()->prettyPrint($indentLevel + 1);
        }

        return '{' . \PHP_EOL . $innerIndent . \implode(',' . \PHP_EOL . $innerIndent, $component) . \PHP_EOL . $indent . '}';
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        foreach ($this->value as $key => $value) {
            \assert($value instanceof \Graphpinator\Normalizer\Value\ArgumentValue);

            $value->getValue()->applyVariables($variables);
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
            \assert($value instanceof \Graphpinator\Normalizer\Value\ArgumentValue);

            if (!\property_exists($secondObject, $key) || !$value->getValue()->isSame($secondObject->{$key})) {
                return false;
            }
        }

        return true;
    }

    private static function merge(\stdClass $core, \stdClass $supplement) : \stdClass
    {
        foreach ((array) $supplement as $key => $value) {
            if (\property_exists($core, $key)) {
                if ($core->{$key} instanceof \stdClass &&
                    $supplement->{$key} instanceof \stdClass) {
                    $core->{$key} = self::merge($core->{$key}, $supplement->{$key});
                }

                continue;
            }

            $core->{$key} = $value;
        }

        return $core;
    }

    public function __isset(string $name) : bool
    {
        return \property_exists($this->value, $name);
    }

    public function __get(string $name) : \Graphpinator\Normalizer\Value\ArgumentValue
    {
        return $this->value->{$name};
    }

    public function __set(string $name, \Graphpinator\Normalizer\Value\ArgumentValue $value) : void
    {
        if ($value->getArgument() !== $this->type->getArguments()[$name]) {
            throw new \Exception();
        }

        $this->value->{$name} = $value;
    }
}
