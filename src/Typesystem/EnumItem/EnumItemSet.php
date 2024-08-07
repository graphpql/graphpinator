<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\EnumItem;

use Graphpinator\Graphpinator;
use Graphpinator\Typesystem\Exception\EnumItemInvalid;
use Infinityloop\Utils\ImplicitObjectMap;

/**
 * @method EnumItem current() : object
 * @method EnumItem offsetGet($offset) : object
 */
final class EnumItemSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = EnumItem::class;

    public function __construct(
        array $data = [],
        private ?string $enumClass = null,
    )
    {
        parent::__construct($data);

        if (Graphpinator::$validateSchema) {
            $this->validateCaseFormat();
        }
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

    private function validateCaseFormat() : void
    {
        foreach ($this as $enumItem) {
            if (\preg_match('/^[a-zA-Z_]+\w*$/', $enumItem->getName()) !== 1 ||
                \in_array($enumItem->getName(), ['true', 'false', 'null'], true)) {
                throw new EnumItemInvalid($enumItem->getName());
            }
        }
    }
}
