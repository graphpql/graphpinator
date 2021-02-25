<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface ArgumentDefinitionLocation extends TypeSystemDefinition, TypeDependantDefinition
{
    public function validateVariance(
        ?\Graphpinator\Value\ArgumentValueSet $biggerSet,
        ?\Graphpinator\Value\ArgumentValueSet $smallerSet,
    ) : void;

    public function resolveArgumentDefinition(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\ArgumentValue $argumentValue,
    ) : void;
}
