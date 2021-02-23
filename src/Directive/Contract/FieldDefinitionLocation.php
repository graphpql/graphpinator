<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface FieldDefinitionLocation extends TypeSystemDefinition, TypeDependantDefinition
{
    public function validateVariance(
        ?\Graphpinator\Value\ArgumentValueSet $biggerSet,
        ?\Graphpinator\Value\ArgumentValueSet $smallerSet,
    ) : void;

    public function resolveFieldDefinitionBefore(
        \Graphpinator\Value\ResolvedValue $parentValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    public function resolveFieldDefinitionAfter(
        \Graphpinator\Value\ResolvedValue $resolvedValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    public function resolveFieldDefinitionValue(
        \Graphpinator\Value\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;
}
