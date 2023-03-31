<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

final class ListType extends \Graphpinator\Typesystem\Contract\ModifierType
{
    public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return false;
    }

    public function printName() : string
    {
        return '[' . $this->innerType->printName() . ']';
    }

    public function notNull() : NotNullType
    {
        return new NotNullType($this);
    }

    public function getShapingType() : \Graphpinator\Typesystem\Contract\Type
    {
        return $this;
    }

    public function accept(\Graphpinator\Typesystem\Contract\TypeVisitor $visitor) : mixed
    {
        return $visitor->visitList($this);
    }
}
