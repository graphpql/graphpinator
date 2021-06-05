<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface InputObjectLocation extends TypeSystemDefinition
{
    public function validateInputUsage(
        \Graphpinator\Type\InputType $inputType,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function resolveInputObject(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\InputValue $inputValue,
    ) : void;
}
