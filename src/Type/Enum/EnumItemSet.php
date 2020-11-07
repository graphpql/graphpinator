<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Enum;

/**
 * @method \Graphpinator\Type\Enum\EnumItem current() : object
 * @method \Graphpinator\Type\Enum\EnumItem offsetGet($offset) : object
 */
final class EnumItemSet extends \Infinityloop\Utils\ImplicitObjectMap implements \Graphpinator\Printable\PrintableSet
{
    protected const INNER_CLASS = EnumItem::class;

    public function getArray() : array
    {
        $return = [];

        foreach ($this as $enumItem) {
            $return[] = $enumItem->getName();
        }

        return $return;
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
