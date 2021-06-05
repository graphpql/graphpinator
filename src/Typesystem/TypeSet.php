<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

/**
 * Class TypeSet which is type safe container for ConcreteTypes.
 *
 * @method \Graphpinator\Type\Type current() : object
 * @method \Graphpinator\Type\Type offsetGet($offset) : object
 */
final class TypeSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Type\Type::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
