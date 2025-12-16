<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\ModifierType;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Contract\TypeVisitor;

final class ListType extends ModifierType
{
    #[\Override]
    public function isInstanceOf(Type $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return false;
    }

    #[\Override]
    public function printName() : string
    {
        return '[' . $this->innerType->printName() . ']';
    }

    public function notNull() : NotNullType
    {
        return new NotNullType($this);
    }

    #[\Override]
    public function getShapingType() : Type
    {
        return $this;
    }

    #[\Override]
    public function accept(TypeVisitor $visitor) : mixed
    {
        return $visitor->visitList($this);
    }
}
