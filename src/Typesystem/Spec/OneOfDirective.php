<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use \Graphpinator\Value\ArgumentValueSet;
use \Graphpinator\Value\InputValue;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in oneOf directive')]
final class OneOfDirective extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\InputObjectLocation
{
    protected const NAME = 'oneOf';

    public function validateInputUsage(\Graphpinator\Typesystem\InputType $inputType, ArgumentValueSet $arguments) : bool
    {
        foreach ($inputType->getArguments() as $argument) {
            if ($argument->getType() instanceof \Graphpinator\Typesystem\NotNullType ||
                $argument->getDefaultValue() instanceof \Graphpinator\Value\ArgumentValue) {
                throw new \Graphpinator\Typesystem\Exception\OneOfInputInvalidFields();
            }
        }
    }

    public function resolveInputObject(ArgumentValueSet $arguments, InputValue $inputValue) : void
    {
        $currentCount = 0;

        foreach ($inputValue as $innerValue) {
            \assert($innerValue instanceof \Graphpinator\Value\ArgumentValue);

            if ($currentCount >= 1 || $innerValue->getValue() instanceof \Graphpinator\Value\NullValue) {
                throw new \Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied();
            }

            ++$currentCount;
        }
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([]);
    }
}
