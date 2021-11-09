<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class LeafType extends ConcreteType implements
    \Graphpinator\Typesystem\Contract\Inputable,
    \Graphpinator\Typesystem\Contract\Outputable
{
    abstract public function validateNonNullValue(mixed $rawValue) : bool;
}
