<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class ConcreteType extends \Graphpinator\Typesystem\Contract\NamedType
{
    public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof \Graphpinator\Typesystem\NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }
}
