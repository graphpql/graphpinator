<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\Contract\ModifierType;

final class ListType extends ModifierType
{
    public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return false;
    }

    public function printName() : string
    {
        return '[' . $this->innerType->printName() . ']';
    }

    public function notNull() : \Graphpinator\Typesystem\NotNullType
    {
        return new \Graphpinator\Typesystem\NotNullType($this);
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
