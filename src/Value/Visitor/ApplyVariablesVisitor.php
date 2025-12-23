<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Visitor;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Location\InputObjectLocation;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Contract\InputedValueVisitor;
use Graphpinator\Value\EnumValue;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\ListValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\VariableValue;

/**
 * @implements InputedValueVisitor<null>
 */
final readonly class ApplyVariablesVisitor implements InputedValueVisitor
{
    public function __construct(
        private VariableValueSet $variableValueSet,
    )
    {
    }

    #[\Override]
    public function visitNull(NullValue $nullValue) : null
    {
        return null;
    }

    #[\Override]
    public function visitList(ListValue $listValue) : null
    {
        foreach ($listValue as $value) {
            \assert($value instanceof InputedValue);

            $value->accept($this);
        }

        return null;
    }

    #[\Override]
    public function visitScalar(ScalarValue $scalarValue) : null
    {
        return null;
    }

    #[\Override]
    public function visitEnum(EnumValue $enumValue) : null
    {
        return null;
    }

    #[\Override]
    public function visitInput(InputValue $inputValue) : null
    {
        foreach ($inputValue as $argumentValue) {
            $argumentValue->applyVariables($this->variableValueSet);
        }

        foreach ($inputValue->getType()->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof InputObjectLocation);
            $directive->resolveInputObject($directiveUsage->getArgumentValues(), $inputValue);
        }

        return null;
    }

    #[\Override]
    public function visitVariable(VariableValue $variableValue) : null
    {
        $variableValue->setVariableValue($this->variableValueSet);

        return null;
    }
}
