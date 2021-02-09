<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface ExecutableDefinition extends Definition, TypeDependantDefinition
{
    public function resolveFieldBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveFieldAfter(
        \Graphpinator\Value\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;
}
