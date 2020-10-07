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
        \Graphpinator\Value\FieldValue $parentResult
    ) : \Graphpinator\Value\LeafValue
    {
        if ($requestedFields instanceof \Graphpinator\Normalizer\FieldSet) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnLeaf();
        }

        return $parentResult->getValue();
    }

    final public function createInputableValue($rawValue) : \Graphpinator\Value\InputableValue
    {
        return \Graphpinator\Resolver\Value\LeafValue::create($rawValue, $this);
    }

    final public function createResolvableValue($rawValue) : \Graphpinator\Value\ResolvableValue
    {
        return \Graphpinator\Resolver\Value\LeafValue::create($rawValue, $this);
    }
}
