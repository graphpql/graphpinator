<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

/**
 * @method FragmentSpread current() : object
 * @method FragmentSpread offsetGet($offset) : object
 */
final class FragmentSpreadSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = FragmentSpread::class;

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
