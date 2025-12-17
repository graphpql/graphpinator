<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\ListType;

abstract class ModifierType implements Type
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

    public function list() : ListType
    {
        return new ListType($this);
    }
}
