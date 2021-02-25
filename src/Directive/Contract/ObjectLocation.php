<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface ObjectLocation extends TypeSystemDefinition, TypeDependantDefinition
{
    public function resolveObject(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : void;
}
