<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class ModifierType implements
    \Graphpinator\Typesystem\Contract\Inputable,
    \Graphpinator\Typesystem\Contract\Outputable
{
    public function __construct(protected \Graphpinator\Typesystem\Contract\Type $innerType)
    {
    }

    public function getInnerType() : \Graphpinator\Typesystem\Contract\Type
    {
        return $this->innerType;
    }

    public function getNamedType() : \Graphpinator\Typesystem\Contract\NamedType
    {
        return $this->innerType->getNamedType();
    }

    public function isInputable() : bool
    {
        return $this->innerType->isInputable();
    }

    public function list() : \Graphpinator\Typesystem\ListType
    {
        return new \Graphpinator\Typesystem\ListType($this);
    }
}
