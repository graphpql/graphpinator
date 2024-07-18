<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\ModifierType;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Contract\TypeVisitor;

final class NotNullType extends ModifierType
{
    public function isInstanceOf(Type $type) : bool
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

    public function getShapingType() : Type
    {
        return $this->getInnerType()->getShapingType();
    }

    public function accept(TypeVisitor $visitor) : mixed
    {
        return $visitor->visitNotNull($this);
    }
}
