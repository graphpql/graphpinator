<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

/**
 * Class ConcreteSet which is type safe container for ConcreteTypes.
 *
 * @method \Graphpinator\Type\Contract\ConcreteDefinition current() : object
 * @method \Graphpinator\Type\Contract\ConcreteDefinition offsetGet($offset) : object
 */
final class ConcreteSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Type\Contract\ConcreteDefinition::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
