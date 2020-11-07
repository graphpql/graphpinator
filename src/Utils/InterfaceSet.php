<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

/**
 * Class InterfaceSet which is type safe container for InterfaceTypes.
 *
 * @method \Graphpinator\Type\InterfaceType current() : object
 * @method \Graphpinator\Type\InterfaceType offsetGet($offset) : object
 */
final class InterfaceSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Type\InterfaceType::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
