<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\Contract\ModifierType;

final class NotNullType extends ModifierType
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
