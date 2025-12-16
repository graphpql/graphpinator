<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

use Infinityloop\Utils\ImplicitObjectMap;

/**
 * @method Field current() : object
 * @method Field offsetGet($offset) : object
 */
class FieldSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = Field::class;

    #[\Override]
    protected function getKey(object $object) : string
    {
        \assert($object instanceof Field);

        return $object->getName();
    }
}
