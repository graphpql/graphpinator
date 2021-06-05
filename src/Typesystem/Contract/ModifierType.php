<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class ModifierType implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Outputable
{
    public function __construct(protected \Graphpinator\Type\Contract\Definition $innerType)
    {
    }

    public function getInnerType() : \Graphpinator\Type\Contract\Definition
    {
        return $this->innerType;
    }

    public function getNamedType() : \Graphpinator\Type\Contract\NamedType
    {
        return $this->innerType->getNamedType();
    }

    public function isInputable() : bool
    {
        return $this->innerType->isInputable();
    }

    public function list() : \Graphpinator\Type\ListType
    {
        return new \Graphpinator\Type\ListType($this);
    }
}
