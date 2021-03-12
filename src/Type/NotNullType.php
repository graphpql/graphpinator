<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class NotNullType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
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

    public function getShapingType() : \Graphpinator\Type\Contract\Definition
    {
        return $this->getInnerType();
    }

    public function accept(\Graphpinator\Typesystem\TypeVisitor $visitor) : mixed
    {
        return $visitor->visitNotNull($this);
    }
}
