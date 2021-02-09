<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface ObjectLocation extends TypeSystemDefinition, TypeDependantDefinition
{
    public function resolveObject(
        \Graphpinator\Value\TypeValue $typeValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;
}
