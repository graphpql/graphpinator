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
        return parent::offsetGet($offset);
    }

    protected function getKey($object)
    {
        return $object->getName();
    }
}
