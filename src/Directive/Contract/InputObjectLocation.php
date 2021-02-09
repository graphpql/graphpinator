<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface InputObjectLocation extends TypeSystemDefinition, TypeDependantDefinition
{
    public function resolveInputObject(
        \Graphpinator\Value\InputValue $inputValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;
}
