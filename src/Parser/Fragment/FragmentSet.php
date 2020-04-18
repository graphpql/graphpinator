<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

final class FragmentSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Fragment::class;

    public function current() : Fragment
    {
        return parent::current();
    }

    public function offsetGet($offset) : Fragment
    {
        return parent::offsetGet($offset);
    }

    protected function getKey($object)
    {
        return $object->getName();
    }
}
