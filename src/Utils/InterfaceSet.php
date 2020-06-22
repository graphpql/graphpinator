<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

/**
 * Class InterfaceSet which is type safe container for InterfaceTypes.
 */
final class InterfaceSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = \Graphpinator\Type\InterfaceType::class;

    public function current() : \Graphpinator\Type\InterfaceType
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Type\InterfaceType
    {
        return parent::offsetGet($offset);
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    protected function getKey($object)
    {
        return $object->getName();
    }
}
