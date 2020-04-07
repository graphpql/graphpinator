<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class FieldSetResult extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = FieldResult::class;

    public function current() : FieldResult
    {
        return parent::current();
    }

    public function offsetGet($offset) : FieldResult
    {
        return parent::offsetGet($offset);
    }
}
