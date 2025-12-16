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

    #[\Override]
    protected function getKey(object $object) : string
    {
        \assert($object instanceof EnumItem);

        return $object->getName();
    }

    private function validateCaseFormat() : void
    {
        foreach ($this as $enumItem) {
            $nameLexicallyInvalid = \preg_match('/^[a-zA-Z_]+\w*$/', $enumItem->getName()) !== 1; // @phpstan-ignore theCodingMachineSafe.function
            $nameKeyword = \in_array($enumItem->getName(), ['true', 'false', 'null'], true);

            if ($nameLexicallyInvalid || $nameKeyword) {
                throw new EnumItemInvalid($enumItem->getName());
            }
        }
    }
}
