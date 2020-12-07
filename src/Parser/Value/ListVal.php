<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ListVal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private array $value,
    ) {}

    public function getValue() : array
    {
        return $this->value;
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $value) {
            \assert($value instanceof Value);

            $return[] = $value->getRawValue();
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
    ) : \Graphpinator\Value\ListInputedValue
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
            return $this->createInputedValue($type->getInnerType(), $variableSet);
        }

        if (!$type instanceof \Graphpinator\Type\ListType) {
            throw new \Exception();
        }

        return \Graphpinator\Value\ListInputedValue::fromParsed($type, $this, $variableSet);
    }
}
