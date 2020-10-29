<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

abstract class ListValue implements \Graphpinator\Value\Value, \Iterator, \ArrayAccess
{
    use \Nette\SmartObject;

    protected \Graphpinator\Type\ListType $type;
    protected array $value;

    public function current() : InputedValue
    {
        return \current($this->value);
    }

    public function next() : void
    {
        \next($this->value);
    }

    public function key() : int
    {
        return \key($this->value);
    }

    public function valid() : bool
    {
        return \key($this->value) !== null;
    }

    public function rewind() : void
    {
        \reset($this->value);
    }

    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->value);
    }

    public function offsetGet($offset) : InputedValue
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
