<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class FragmentSpreadSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public function current() : FragmentSpread
    {
        return parent::current();
    }

    public function offsetGet($offset) : FragmentSpread
    {
        return parent::offsetGet($offset);
    }
}
