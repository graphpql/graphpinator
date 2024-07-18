<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Infinityloop\Utils\ImplicitObjectMap;

/**
 * Class InterfaceSet which is type safe container for InterfaceTypes.
 *
 * @method InterfaceType current() : object
 * @method InterfaceType offsetGet($offset) : object
 */
final class InterfaceSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = InterfaceType::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
