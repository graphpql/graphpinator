<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

final class NotNullType extends \Graphpinator\Typesystem\Contract\ModifierType
{
    public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return $this->innerType->isInstanceOf($type);
    }

    public function printName() : string
    {
        return $this->innerType->printName() . '!';
    }

    public function getShapingType() : \Graphpinator\Typesystem\Contract\Type
    {
        return $this->getInnerType()->getShapingType();
    }

    public function accept(\Graphpinator\Typesystem\Contract\TypeVisitor $visitor) : mixed
    {
        return $visitor->visitNotNull($this);
    }
}
