<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface FieldDefinitionLocation extends \Graphpinator\Typesystem\Contract\TypesystemDirective
{
    public function validateFieldUsage(
        \Graphpinator\Field\Field $field,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function validateVariance(
        ?\Graphpinator\Value\ArgumentValueSet $biggerSet,
        ?\Graphpinator\Value\ArgumentValueSet $smallerSet,
    ) : void;

    public function resolveFieldDefinitionStart(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\ResolvedValue $parentValue,
    ) : void;

    public function resolveFieldDefinitionBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\ResolvedValue $parentValue,
        \Graphpinator\Value\ArgumentValueSet $fieldArguments,
    ) : void;

    public function resolveFieldDefinitionAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\ResolvedValue $resolvedValue,
        \Graphpinator\Value\ArgumentValueSet $fieldArguments,
    ) : void;

    public function resolveFieldDefinitionValue(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : void;
}
