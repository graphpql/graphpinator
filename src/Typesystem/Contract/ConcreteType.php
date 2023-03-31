<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class ConcreteType extends NamedType
{
    public function isInstanceOf(Type $type) : bool
    {
        return $type instanceof static;
    }
}
