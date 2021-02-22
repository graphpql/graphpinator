<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class ModifierDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Resolvable
{
    protected \Graphpinator\Type\Contract\Definition $innerType;

    public function __construct(\Graphpinator\Type\Contract\Definition $innerType)
    {
        $this->innerType = $innerType;
    }

    public function getInnerType() : \Graphpinator\Type\Contract\Definition
    {
        return $this->innerType;
    }

    public function getNamedType() : \Graphpinator\Type\Contract\NamedDefinition
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
