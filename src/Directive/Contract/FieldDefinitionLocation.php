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
