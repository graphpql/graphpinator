<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class VariableValueSet implements \ArrayAccess
{
    use \Nette\SmartObject;

    private array $array = [];

    public function __construct(
        \Graphpinator\Normalizer\Variable\VariableSet $definedVariables,
        \stdClass $providedValues
    )
    {
        foreach ($definedVariables as $variable) {
            $this->array[$variable->getName()] = $variable->createInputedValue($providedValues);
        }
    }

    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->array);
    }

    public function offsetGet($offset) : \Graphpinator\Value\InputedValue
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Item doesnt exist.');
        }

        return $this->array[$offset];
    }

    public function offsetSet($offset, $value) : void
    {
        if (!\is_string($offset) || !$value instanceof \Graphpinator\Value\InputedValue) {
            throw new \Exception('Invalid input.');
        }

        $this->array[$offset] = $value;
    }

    public function offsetUnset($offset) : void
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }
}
