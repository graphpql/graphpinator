<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Argument\ArgumentValue;

final class InputValue implements InputedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\InputType $type;
    private \stdClass $value;

    public function __construct(\Graphpinator\Type\InputType $type, \stdClass $rawValue)
    {
        $rawValue = self::merge($rawValue, (object) $type->getArguments()->getRawDefaults());

        foreach ($rawValue as $name => $temp) {
            if (isset($type->getArguments()[$name])) {
                continue;
            }

            throw new \Exception('Unknown field for input value');
        }

        $value = new \stdClass();

        foreach ($type->getArguments() as $argument) {
            $value->{$argument->getName()} = new ArgumentValue($argument, $rawValue->{$argument->getName()} ?? null);
        }

        $this->type = $type;
        $this->value = $value;

        $type->validateConstraints($this);
    }

    public function getRawValue() : \stdClass
    {
        $return = new \stdClass();

        foreach ($this->value as $fieldName => $fieldValue) {
            \assert($fieldValue instanceof ArgumentValue);

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

        foreach ($this->value as $key => $value) {
            \assert($value instanceof ArgumentValue);

            $component[$key] = $key . ':' . $value->getValue()->printValue();
        }

        return '{' . \implode(',', $component) . '}';
    }

    public function __isset($offset) : bool
    {
        return \property_exists($this->value, $offset);
    }

    public function __get($offset) : ArgumentValue
    {
        return $this->value->{$offset};
    }

    private static function merge(\stdClass $core, \stdClass $supplement) : \stdClass
    {
        foreach ($supplement as $key => $value) {
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
}
