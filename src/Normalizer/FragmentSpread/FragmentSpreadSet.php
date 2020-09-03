<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\FragmentSpread;

final class FragmentSpreadSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = FragmentSpread::class;

    public function current() : FragmentSpread
    {
        return parent::current();
    }

    public function offsetGet($offset) : FragmentSpread
    {
        return parent::offsetGet($offset);
    }
}
