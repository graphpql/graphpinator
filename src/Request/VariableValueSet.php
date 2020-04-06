<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class VariableValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(
        \Graphpinator\Request\Variable\VariableSet $definedVariables,
        \Infinityloop\Utils\Json $providedValues
    ) {
        $data = [];

        foreach ($definedVariables as $variable) {
            $data[$variable->getName()] = $variable->createValue($providedValues);
        }

        parent::__construct($data);
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
