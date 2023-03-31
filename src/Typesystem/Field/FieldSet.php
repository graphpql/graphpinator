<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

/**
 * @method Field current() : object
 * @method Field offsetGet($offset) : object
 */
class FieldSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Field::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
