<?php

declare(strict_types = 1);

namespace PGQL\Field;

final class FieldSet implements \Iterator, \ArrayAccess, \Countable
{
    use \Nette\SmartObject;

    private array $fields = [];

    public function __construct(array $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof Field) {
                $this->fields[$field->getName()] = $field;

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : Field
    {
        return \current($this->fields);
    }

    public function next() : void
    {
        \next($this->fields);
    }

    public function key() : int
    {
        return \key($this->fields);
    }

    public function valid() : bool
    {
        return \key($this->fields) !== null;
    }

    public function rewind() : void
    {
        \reset($this->fields);
    }

    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->fields);
    }

    public function offsetGet($offset) : Field
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown field.');
        }

        return $this->fields[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception();
    }

    public function offsetUnset($offset)
    {
        throw new \Exception();
    }

    public function count()
    {
        return \count($this->fields);
    }
}
