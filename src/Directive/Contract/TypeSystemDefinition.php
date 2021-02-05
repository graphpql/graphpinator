<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface TypeSystemDefinition extends Definition
{
    public function validateType(
        ?\Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function resolveFieldDefinitionBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    public function resolveFieldDefinitionAfter(
        \Graphpinator\Value\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    public function resolveObject(
        \Graphpinator\Value\TypeValue $typeValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    public function resolveInputObject(
        \Graphpinator\Value\InputValue $inputValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    public function resolveArgumentDefinition(
        \Graphpinator\Value\ArgumentValue $argumentValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;
}
