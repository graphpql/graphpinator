<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

interface FragmentSpread
{
    public function normalize(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpread;
}
