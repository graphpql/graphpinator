<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\EnumItem;

/**
 * @method \Graphpinator\Typesystem\EnumItem\EnumItem current() : object
 * @method \Graphpinator\Typesystem\EnumItem\EnumItem offsetGet($offset) : object
 */
final class EnumItemSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = EnumItem::class;

    public function __construct(
        array $data = [],
        private ?string $enumClass = null,
    )
    {
        parent::__construct($data);
    }

    public function getEnumClass() : ?string
    {
        return $this->enumClass;
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
