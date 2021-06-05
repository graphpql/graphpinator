<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

/**
 * Class InterfaceSet which is type safe container for InterfaceTypes.
 *
 * @method \Graphpinator\Typesystem\InterfaceType current() : object
 * @method \Graphpinator\Typesystem\InterfaceType offsetGet($offset) : object
 */
final class InterfaceSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Typesystem\InterfaceType::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
