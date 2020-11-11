<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

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

    public function offsetSet($offset, $object) : void
    {
        if (!\is_string($offset) || !$object instanceof \Graphpinator\Value\InputedValue) {
            throw new \Exception('Invalid input.');
        }

        $this->array[$offset] = $object;
    }

    public function offsetUnset($offset) : void
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }
}
