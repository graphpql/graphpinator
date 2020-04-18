<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

final class FragmentSet extends \Graphpinator\Utils\ClassSet
{
    public const INNER_CLASS = Fragment::class;

    public function __construct(array $data)
    {
        parent::__construct($data);

        foreach ($this as $fragment) {
            $fragment->validateCycles($this, []);
        }
    }

    public function current() : Fragment
    {
        return parent::current();
    }

    public function offsetGet($offset) : Fragment
    {
        return parent::offsetGet($offset);
    }
}
