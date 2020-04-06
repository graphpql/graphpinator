<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

final class ConcreteSet extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = \Graphpinator\Type\Contract\ConcreteDefinition::class;

    public function current() : \Graphpinator\Type\Contract\ConcreteDefinition
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Type\Contract\ConcreteDefinition
    {
        return parent::offsetGet($offset);
    }
}
