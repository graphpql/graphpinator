<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

class FieldSet extends \Infinityloop\Utils\ObjectSet implements \Graphpinator\Printable\PrintableSet
{
    protected const INNER_CLASS = Field::class;

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Field\FieldNotDefined();
        }

        return $this->array[$offset];
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
