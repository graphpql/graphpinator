<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

/**
 * Class InterfaceSet which is type safe container for InterfaceTypes.
 */
final class InterfaceSet extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = \Graphpinator\Type\InterfaceType::class;

    public function current() : \Graphpinator\Type\InterfaceType
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Type\InterfaceType
    {
        return parent::offsetGet($offset);
    }
}
