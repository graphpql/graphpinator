<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

abstract class LeafConstraintDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\TypeSystemDefinition
{
    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
            ],
            false,
        );
    }

    public function resolveFieldDefinitionBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionAfter(
        \Graphpinator\Value\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        $this->validate($fieldValue->getValue(), $arguments);
    }

    public function resolveObject(
        \Graphpinator\Value\TypeValue $typeValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveInputObject(
        \Graphpinator\Value\InputValue $inputValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveArgumentDefinition(
        \Graphpinator\Value\ArgumentValue $argumentValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        $this->validate($argumentValue->getValue(), $arguments);
    }

    abstract protected function validate(
        \Graphpinator\Value\Value $value,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;
}
