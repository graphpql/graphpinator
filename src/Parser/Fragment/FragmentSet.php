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
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Parser\FragmentNotDefined();
        }

        return $this->array[$offset];
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
