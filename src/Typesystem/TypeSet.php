<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Infinityloop\Utils\ImplicitObjectMap;

/**
 * Class TypeSet, which is type safe container for ConcreteTypes.
 *
 * @method Type current() : object
 * @method Type offsetGet($offset) : object
 */
final class TypeSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = Type::class;

    #[\Override]
    protected function getKey(object $object) : string
    {
        \assert($object instanceof Type);

        return $object->getName();
    }
}
