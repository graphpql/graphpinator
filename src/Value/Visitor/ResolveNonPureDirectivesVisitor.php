<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Visitor;

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
final readonly class ResolveNonPureDirectivesVisitor implements InputedValueVisitor
{
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
            $argumentValue->resolveNonPureDirectives();
        }

        return null;
    }

    #[\Override]
    public function visitVariable(VariableValue $variableValue) : null
    {
        return $variableValue->getConcreteValue()->accept($this);
    }
}
