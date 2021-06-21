<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

abstract class ListValue implements \Graphpinator\Value\Value, \IteratorAggregate, \ArrayAccess
{
    use \Nette\SmartObject;

    public function __construct(
        protected \Graphpinator\Typesystem\ListType $type,
        protected array $value,
    )
    {
    }

    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->value);
    }

    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->value);
    }

    public function offsetGet($offset) : Value
    {
        return $this->value[$offset];
    }

    public function offsetSet($offset, $value) : void
    {
        $this->value[$offset] = $value;
    }

    public function offsetUnset($offset) : void
    {
        unset($this->value[$offset]);
    }
}
