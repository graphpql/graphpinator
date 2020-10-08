<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class VariableValueSet extends \Infinityloop\Utils\ObjectSet
{
    public function __construct(
        \Graphpinator\Normalizer\Variable\VariableSet $definedVariables,
        \stdClass $providedValues
    )
    {
        foreach ($definedVariables as $variable) {
            $this->array[$variable->getName()] = $variable->createInputedValue($providedValues);
        }
    }

    public function current() : \Graphpinator\Value\InputedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Value\InputedValue
    {
        return parent::offsetGet($offset);
    }
}
