<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class LeafType extends ConcreteType
{
    abstract public function validateNonNullValue(mixed $rawValue) : bool;
}
