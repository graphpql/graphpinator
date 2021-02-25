<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface FieldLocation extends ExecutableDefinition, TypeDependantDefinition
{
    public function resolveFieldBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveFieldAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : string;
}
