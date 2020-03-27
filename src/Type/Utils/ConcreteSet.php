<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

final class ConcreteSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $types)
    {
        foreach ($types as $type) {
            if ($type instanceof \PGQL\Type\ConcreteDefinition) {
                $this->array[$type->getName()] = $type;

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : \PGQL\Type\ConcreteDefinition
    {
        return parent::current();
    }

    public function offsetGet($offset) : \PGQL\Type\ConcreteDefinition
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown type.');
        }

        return $this->array[$offset];
    }
}
