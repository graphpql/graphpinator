<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class GivenValueSet implements \Iterator, \ArrayAccess
{
    private array $values = [];

    public function __construct(array $values)
    {
        foreach ($values as $value) {
            if ($value instanceof \PGQL\Value\GivenValue) {
                $this->values[$value->getName()] = $value;

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : GivenValue
    {
        return \current($this->values);
    }

    public function next() : void
    {
        \next($this->values);
    }

    public function key() : int
    {
        return \key($this->values);
    }

    public function valid() : bool
    {
        return \key($this->values) !== null;
    }

    public function rewind() : void
    {
        \reset($this->values);
    }

    public function offsetExists($name) : bool
    {
        return \array_key_exists($name, $this->values);
    }

    public function offsetGet($offset) : GivenValue
    {
        return $this->values[$offset];
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
