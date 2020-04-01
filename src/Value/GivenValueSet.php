<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class GivenValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $values)
    {
        foreach ($values as $value) {
            if ($value instanceof \Graphpinator\Value\GivenValue) {
                $this->appendUnique($value->getName(), $value);

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : GivenValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : GivenValue
    {
        return parent::offsetGet($offset);
    }
}
