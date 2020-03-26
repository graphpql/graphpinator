<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class ModifierDefinition implements Definition
{
    protected Definition $innerType;

    public function __construct(Definition $innerType)
    {
        $this->innerType = $innerType;
    }

    public function getInnerType() : Definition
    {
        return $this->innerType;
    }

    public function getNamedType() : NamedDefinition
    {
        return $this->innerType->getNamedType();
    }

    public function list() : ListType
    {
        return new ListType($this);
    }
}
