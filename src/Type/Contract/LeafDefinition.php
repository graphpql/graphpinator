<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class LeafDefinition extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Resolvable
{
    use \Graphpinator\Type\Contract\TResolvable;

    final public function resolve(
        ?\Graphpinator\Normalizer\FieldSet $requestedFields,
        \Graphpinator\Value\ResolvedValue $parentResult
    ) : \Graphpinator\Value\LeafValue
    {
        if ($requestedFields instanceof \Graphpinator\Normalizer\FieldSet) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnLeaf();
        }

        return $parentResult;
    }

    final public function createInputedValue($rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($this);
        }

        return new \Graphpinator\Value\LeafValue($this, $rawValue);
    }

    final public function createResolvedValue($rawValue) : \Graphpinator\Value\ResolvedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($this);
        }

        return new \Graphpinator\Value\LeafValue($this, $rawValue);
    }
}
