<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

final class ConcreteSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $types)
    {
        foreach ($types as $type) {
            if ($type instanceof \Graphpinator\Type\Contract\ConcreteDefinition) {
                $this->appendUnique($type->getName(), $type);

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : \Graphpinator\Type\Contract\ConcreteDefinition
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Type\Contract\ConcreteDefinition
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown type.');
        }

        return $this->array[$offset];
    }
}
