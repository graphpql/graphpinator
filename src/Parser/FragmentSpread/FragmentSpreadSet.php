<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

/**
 * @method \Graphpinator\Parser\FragmentSpread\FragmentSpread current() : object
 * @method \Graphpinator\Parser\FragmentSpread\FragmentSpread offsetGet($offset) : object
 */
final class FragmentSpreadSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = FragmentSpread::class;

    public function normalize(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet
    {
        $normalized = [];

        foreach ($this as $spread) {
            $normalized[] = $spread->normalize($parentType, $typeContainer, $fragmentDefinitions, $variableSet);
        }

        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet($normalized);
    }
}
