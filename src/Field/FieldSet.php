<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

class FieldSet extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = Field::class;

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        return parent::offsetGet($offset);
    }

    public function offsetSet($offset, $value) : void
    {
        $this->appendUnique($offset, $value);
    }
}
