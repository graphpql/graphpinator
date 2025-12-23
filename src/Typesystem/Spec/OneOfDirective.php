<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied;
use Graphpinator\Typesystem\Exception\OneOfInputInvalidFields;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\Location\InputObjectLocation;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\NullValue;

#[Description('Built-in oneOf directive')]
final class OneOfDirective extends Directive implements InputObjectLocation
{
    protected const NAME = 'oneOf';

    #[\Override]
    public function validateInputUsage(InputType $inputType, ArgumentValueSet $arguments) : bool
    {
        foreach ($inputType->getArguments() as $argument) {
            if ($argument->getType() instanceof NotNullType ||
                $argument->getDefaultValue() instanceof ArgumentValue) {
                throw new OneOfInputInvalidFields();
            }
        }

        return true;
    }

    #[\Override]
    public function resolveInputObject(ArgumentValueSet $arguments, InputValue $inputValue) : void
    {
        $currentCount = 0;

        foreach ($inputValue as $innerValue) {
            if ($currentCount >= 1 || $innerValue->getValue() instanceof NullValue) {
                throw new OneOfDirectiveNotSatisfied();
            }

            ++$currentCount;
        }

        if ($currentCount !== 1) {
            throw new OneOfDirectiveNotSatisfied();
        }
    }

    #[\Override]
    protected function getFieldDefinition() : ArgumentSet
    {
        return new ArgumentSet([]);
    }
}
