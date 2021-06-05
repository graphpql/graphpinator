<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

/**
 * Class TypeSet which is type safe container for ConcreteTypes.
 *
 * @method \Graphpinator\Typesystem\Type current() : object
 * @method \Graphpinator\Typesystem\Type offsetGet($offset) : object
 */
final class TypeSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Typesystem\Type::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
