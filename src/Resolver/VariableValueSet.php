<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class VariableValueSet extends \Infinityloop\Utils\ObjectSet
{
    public function __construct(
        \Graphpinator\Normalizer\Variable\VariableSet $definedVariables,
        array $providedValues
    )
    {
        foreach ($definedVariables as $variable) {
            $this->array[$variable->getName()] = $variable->createValue($providedValues);
        }
    }

    public function current() : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Resolver\VariableValueNotDefined();
        }

        return $this->array[$offset];
    }
}
