<?php

declare(strict_types = 1);

namespace Graphpinator\EnumItem;

/**
 * @method \Graphpinator\EnumItem\EnumItem current() : object
 * @method \Graphpinator\EnumItem\EnumItem offsetGet($offset) : object
 */
final class EnumItemSet extends \Infinityloop\Utils\ImplicitObjectMap
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
