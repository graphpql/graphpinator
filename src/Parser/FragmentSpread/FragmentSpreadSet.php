<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

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

    public function normalize(
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet
    {
        $normalized = [];

        foreach ($this as $spread) {
            $normalized[] = $spread->normalize($typeContainer, $fragmentDefinitions);
        }

        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet($normalized);
    }
}
