<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ValidatedValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $values)
    {
        foreach ($values as $value) {
            if ($value instanceof ValidatedValue) {
                $this->appendUnique($value->getName(), $value);

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : ValidatedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return parent::offsetGet($offset);
    }
}
