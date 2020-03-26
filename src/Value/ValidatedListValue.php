<?php

declare(strict_types = 1);

namespace PGQL\Value;

class ValidatedListValue extends \PGQL\Value\ValidatedValue implements \Iterator, \ArrayAccess
{
    public function __construct(iterable $list, \PGQL\Type\Definition $type)
    {
        $value = [];

        foreach ($list as $item) {
            $value[] = new ValidatedValue($item, $type);
        }

        $this->value = $value;
        $this->type = $type;
    }

    public function current() : ValidatedValue
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

    public function offsetExists($name) : bool
    {
        return \array_key_exists($name, $this->value);
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return $this->value[$offset];
    }

    public function offsetSet($offset, $value) : void
    {
        throw new \Exception();
    }

    public function offsetUnset($offset) : void
    {
        throw new \Exception();
    }
}
