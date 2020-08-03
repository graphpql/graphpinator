<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

final class ResolvableFieldSet extends \Graphpinator\Field\FieldSet
{
    protected const INNER_CLASS = ResolvableField::class;

    public function current() : ResolvableField
    {
        return parent::current();
    }

    public function offsetGet($offset) : ResolvableField
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Field\ResolvableFieldNotDefined();
        }

        return $this->array[$offset];
    }
}
