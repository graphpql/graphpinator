<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

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
