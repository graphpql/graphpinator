<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

/**
 * @method \Graphpinator\Parser\Value\ArgumentValue current() : object
 * @method \Graphpinator\Parser\Value\ArgumentValue offsetGet($offset) : object
 */
final class ArgumentValueSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Parser\Value\ArgumentValue::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
