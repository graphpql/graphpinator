<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class LeafDefinition extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Resolvable
{
    final public function resolve(
        ?\Graphpinator\Normalizer\FieldSet $requestedFields,
        \Graphpinator\Value\ResolvedValue $parentResult
    ) : \Graphpinator\Value\LeafValue
    {
        return $parentResult;
    }

    abstract public function validateNonNullValue($rawValue) : bool;

    public function createInputedValue($rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($this);
        }

        return new \Graphpinator\Value\LeafValue($this, $rawValue, true);
    }

    final public function createResolvedValue($rawValue) : \Graphpinator\Value\ResolvedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($this);
        }

        return new \Graphpinator\Value\LeafValue($this, $rawValue, false);
    }

    final public function getField(string $name) : \Graphpinator\Field\Field
    {
        throw new \Graphpinator\Exception\Normalizer\SelectionOnLeaf();
    }
}
