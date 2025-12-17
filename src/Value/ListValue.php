<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ListType;

abstract class ListValue implements Value, \IteratorAggregate
{
    public function __construct(
        protected ListType $type,
        protected array $value,
    )
    {
    }

    #[\Override]
    public function getType() : ListType
    {
        return $this->type;
    }

    #[\Override]
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->value);
    }
}
