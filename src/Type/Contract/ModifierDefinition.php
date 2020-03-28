<?php

declare(strict_types = 1);

namespace PGQL\Type\Contract;

abstract class ModifierDefinition implements \PGQL\Type\Contract\Definition
{
    protected \PGQL\Type\Contract\Definition $innerType;

    public function __construct(\PGQL\Type\Contract\Definition $innerType)
    {
        $this->innerType = $innerType;
    }

    public function getInnerType() : \PGQL\Type\Contract\Definition
    {
        return $this->innerType;
    }

    public function getNamedType() : \PGQL\Type\Contract\NamedDefinition
    {
        return $this->innerType->getNamedType();
    }

    public function list() : \PGQL\Type\ListType
    {
        return new \PGQL\Type\ListType($this);
    }
}
