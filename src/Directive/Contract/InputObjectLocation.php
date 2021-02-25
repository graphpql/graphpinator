<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface InputObjectLocation extends TypeSystemDefinition, TypeDependantDefinition
{
    public function resolveInputObject(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\InputValue $inputValue,
    ) : void;
}
