<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ValidatedValueSet extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = ValidatedValue::class;

    public function current() : ValidatedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return parent::offsetGet($offset);
    }
}
