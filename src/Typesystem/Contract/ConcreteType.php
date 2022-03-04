<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class ConcreteType extends \Graphpinator\Typesystem\Contract\NamedType
{
    public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        return $type instanceof static;
    }
}
