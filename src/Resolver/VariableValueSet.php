<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class VariableValueSet extends \Infinityloop\Utils\ObjectSet
{
    public function __construct(
        \Graphpinator\Normalizer\Variable\VariableSet $definedVariables,
        \Infinityloop\Utils\Json $providedValues
    ) {
        foreach ($definedVariables as $variable) {
            $this->array[$variable->getName()] = $variable->createValue($providedValues);
        }
    }

    public function current() : \Graphpinator\Value\ValidatedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Value\ValidatedValue
    {
        return parent::offsetGet($offset);
    }
}
