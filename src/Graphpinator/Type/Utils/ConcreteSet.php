<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Utils;

final class ConcreteSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $types)
    {
        foreach ($types as $type) {
            if ($type instanceof \Infinityloop\Graphpinator\Type\Contract\ConcreteDefinition) {
                $this->appendUnique($type->getName(), $type);

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : \Infinityloop\Graphpinator\Type\Contract\ConcreteDefinition
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Infinityloop\Graphpinator\Type\Contract\ConcreteDefinition
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown type.');
        }

        return $this->array[$offset];
    }
}
