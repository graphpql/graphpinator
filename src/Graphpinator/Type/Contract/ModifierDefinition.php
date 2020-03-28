<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

abstract class ModifierDefinition implements
    \Infinityloop\Graphpinator\Type\Contract\Inputable,
    \Infinityloop\Graphpinator\Type\Contract\Resolvable
{
    protected \Infinityloop\Graphpinator\Type\Contract\Definition $innerType;

    public function __construct(\Infinityloop\Graphpinator\Type\Contract\Definition $innerType)
    {
        $this->innerType = $innerType;
    }

    public function getInnerType() : \Infinityloop\Graphpinator\Type\Contract\Definition
    {
        return $this->innerType;
    }

    public function getNamedType() : \Infinityloop\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->innerType->getNamedType();
    }

    public function isInputable() : bool
    {
        return $this->innerType->isInputable();
    }

    public function isOutputable() : bool
    {
        return $this->innerType->isOutputable();
    }

    public function isInstantiable() : bool
    {
        return $this->innerType->isInstantiable();
    }

    public function isResolvable() : bool
    {
        return $this->innerType->isResolvable();
    }

    public function list() : \Infinityloop\Graphpinator\Type\ListType
    {
        return new \Infinityloop\Graphpinator\Type\ListType($this);
    }
}
