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
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Parser\FragmentSpreadNotDefined();
        }

        return $this->array[$offset];
    }

    public function normalize(
        \Graphpinator\Type\Container\Container $typeContainer,
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
