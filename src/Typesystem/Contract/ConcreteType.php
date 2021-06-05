<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class ConcreteType extends \Graphpinator\Type\Contract\NamedType
{
    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }
}
