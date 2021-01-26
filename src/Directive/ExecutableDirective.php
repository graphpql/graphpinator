<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

interface ExecutableDirective
{
    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool;

    public function resolveFieldBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveFieldAfter(
        \Graphpinator\Field\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;
}
