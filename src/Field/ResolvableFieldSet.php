<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

final class ResolvableFieldSet extends FieldSet
{
    protected const INNER_CLASS = ResolvableField::class;

    public function current() : ResolvableField
    {
        return parent::current();
    }

    public function offsetGet($offset) : ResolvableField
    {
        return parent::offsetGet($offset);
    }
}
