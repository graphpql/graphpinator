<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface FieldLocation extends ExecutableDefinition
{
    public function validateFieldUsage(
        \Graphpinator\Field\Field $field,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function resolveFieldBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveFieldAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : string;
}
