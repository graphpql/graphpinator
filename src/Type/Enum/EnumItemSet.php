<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Enum;

final class EnumItemSet extends \Infinityloop\Utils\ObjectSet implements \Graphpinator\Printable\PrintableSet
{
    protected const INNER_CLASS = EnumItem::class;

    public function current() : EnumItem
    {
        return parent::current();
    }

    public function offsetGet($offset) : EnumItem
    {
        return parent::offsetGet($offset);
    }

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
