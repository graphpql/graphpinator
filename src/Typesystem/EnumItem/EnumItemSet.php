<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\EnumItem;

use Infinityloop\Utils\ImplicitObjectMap;

/**
 * @method EnumItem current() : object
 * @method EnumItem offsetGet($offset) : object
 */
final class EnumItemSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = EnumItem::class;

    /**
     * @param list<EnumItem> $data
     * @param ?string $enumClass
     */
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

    /**
     * @return list<string>
     */
    public function getArray() : array
    {
        $return = [];

        foreach ($this as $enumItem) {
            $return[] = $enumItem->getName();
        }

        return $return;
    }

    #[\Override]
    protected function getKey(object $object) : string
    {
        \assert($object instanceof EnumItem);

        return $object->getName();
    }
}
