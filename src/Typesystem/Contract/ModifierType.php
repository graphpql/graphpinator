<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\ListType;

abstract class ModifierType implements Inputable, Outputable
{
    public function __construct(
        protected Type $innerType,
    )
    {
    }

    public function getInnerType() : Type
    {
        return $this->innerType;
    }

    public function getNamedType() : NamedType
    {
        return $this->innerType->getNamedType();
    }

    public function isInputable() : bool
    {
        return $this->innerType->isInputable();
    }

    public function list() : ListType
    {
        return new ListType($this);
    }
}
