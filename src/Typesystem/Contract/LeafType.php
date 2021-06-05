<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class LeafType extends \Graphpinator\Type\Contract\ConcreteType implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Outputable
{
    abstract public function validateNonNullValue(mixed $rawValue) : bool;
}
