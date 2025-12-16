<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ListType;

abstract class ListValue implements Value, \IteratorAggregate, \ArrayAccess, \Countable
{
    public function __construct(
        protected ListType $type,
        protected array $value,
    )
    {
    }

    #[\Override]
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->value);
    }

    #[\Override]
    public function count() : int
    {
        return \count($this->value);
    }

    #[\Override]
    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->value);
    }

    #[\Override]
    public function offsetGet($offset) : Value
    {
        return $this->value[$offset];
    }

    #[\Override]
    public function offsetSet($offset, $value) : void
    {
        $this->value[$offset] = $value;
    }

    #[\Override]
    public function offsetUnset($offset) : void
    {
        unset($this->value[$offset]);
    }
}
