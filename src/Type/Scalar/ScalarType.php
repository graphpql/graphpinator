<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

abstract class ScalarType extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Resolvable
{
    use \Graphpinator\Type\Contract\TResolvable;

    public function resolve(?\Graphpinator\Normalizer\FieldSet $requestedFields, \Graphpinator\Resolver\FieldResult $parentResult) : \Graphpinator\Value\ValidatedValue
    {
        if ($requestedFields instanceof \Graphpinator\Normalizer\FieldSet) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnLeaf();
        }

        return $parentResult->getResult();
    }

    public function applyDefaults($value)
    {
        return $value;
    }

    public function createValue($rawValue) : \Graphpinator\Value\ValidatedValue
    {
        return \Graphpinator\Value\ScalarValue::create($rawValue, $this);
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::SCALAR;
    }
}
