<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ObjectVal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private \stdClass $value,
    ) {}

    public function getValue() : \stdClass
    {
        return $this->value;
    }

    public function getRawValue() : \stdClass
    {
        $return = new \stdClass();

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            $return->{$key} = $value->getRawValue();
        }

        return $return;
    }

    public function hasVariables() : bool
    {
        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            if ($value->hasVariables()) {
                return true;
            }
        }

        return false;
    }

    public function createInputedValue(
        \Graphpinator\Type\Contract\Inputable $type,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Value\InputValue
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
            return $this->createInputedValue($type->getInnerType(), $variableSet);
        }

        if (!$type instanceof \Graphpinator\Type\InputType) {
            throw new \Exception();
        }

        return \Graphpinator\Value\InputValue::fromParserValue($type, $this, $variableSet);
    }
}
