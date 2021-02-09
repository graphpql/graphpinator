<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface TypeDependantDefinition
{
    public function validateType(
        \Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;
}
