<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class ListType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createInputedValue($rawValue) : \Graphpinator\Value\InputedValue
    {
        if (\is_array($rawValue)) {
            return \Graphpinator\Value\ListInputedValue::fromRaw($this, $rawValue);
        }

        return new \Graphpinator\Value\NullInputedValue($this);
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return false;
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::LIST;
    }

    public function printName() : string
    {
        return '[' . $this->innerType->printName() . ']';
    }

    public function notNull() : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType($this);
    }

    public function getShapingType() : \Graphpinator\Type\Contract\Definition
    {
        return $this;
    }

    public function accept(\Graphpinator\Typesystem\TypeVisitor $visitor) : mixed
    {
        return $visitor->visitList($this);
    }
}
