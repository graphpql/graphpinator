<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

/**
 * @method Fragment current() : object
 * @method Fragment offsetGet($offset) : object
 */
final class FragmentSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Fragment::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
