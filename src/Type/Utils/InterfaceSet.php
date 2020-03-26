<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

final class InterfaceSet implements \Iterator, \ArrayAccess
{
    use \Nette\SmartObject;

    private array $interfaces = [];

    public function __construct(array $interfaces)
    {
        foreach ($interfaces as $interface) {
            if ($interface instanceof \PGQL\Type\InterfaceType) {
                $this->interfaces[$interface->getName()] = $interface;

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : \PGQL\Type\InterfaceType
    {
        return \current($this->interfaces);
    }

    public function next() : void
    {
        \next($this->interfaces);
    }

    public function key() : int
    {
        return \key($this->interfaces);
    }

    public function valid() : bool
    {
        return \key($this->interfaces) !== null;
    }

    public function rewind() : void
    {
        \reset($this->interfaces);
    }

    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->interfaces);
    }

    public function offsetGet($offset) : \PGQL\Type\InterfaceType
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown interface.');
        }

        return $this->interfaces[$offset];
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
