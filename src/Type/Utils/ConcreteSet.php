<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

final class ConcreteSet implements \Iterator, \ArrayAccess
{
    use \Nette\SmartObject;

    private array $types = [];

    public function __construct(array $types)
    {
        foreach ($types as $type) {
            if ($type instanceof \PGQL\Type\ConcreteDefinition) {
                $this->types[$type->getName()] = $type;

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : \PGQL\Type\ConcreteDefinition
    {
        return \current($this->types);
    }

    public function next() : void
    {
        \next($this->types);
    }

    public function key() : int
    {
        return \key($this->types);
    }

    public function valid() : bool
    {
        return \key($this->types) !== null;
    }

    public function rewind() : void
    {
        \reset($this->types);
    }

    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->types);
    }

    public function offsetGet($offset) : \PGQL\Type\ConcreteDefinition
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown type.');
        }

        return $this->types[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception();
    }

    public function offsetUnset($offset)
    {
        throw new \Exception();
    }
}
