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
 * @phpcs:ignore
 * @implements InputedValueVisitor<mixed>
 */
final readonly class GetResolverValueVisitor implements InputedValueVisitor
{
    #[\Override]
    public function visitNull(NullValue $nullValue) : null
    {
        return null;
    }

    #[\Override]
    public function visitList(ListValue $listValue) : array
    {
        $return = [];

        foreach ($listValue as $listItem) {
            \assert($listItem instanceof InputedValue);

            $return[] = $listItem->accept($this);
        }

        return $return;
    }

    #[\Override]
    public function visitScalar(ScalarValue $scalarValue) : mixed
    {
        return $scalarValue->hasResolverValue()
            ? $scalarValue->getResolverValue()
            : $scalarValue->getRawValue();
    }

    #[\Override]
    public function visitEnum(EnumValue $enumValue) : \BackedEnum|string
    {
        return $enumValue->getRawValue();
    }

    #[\Override]
    public function visitInput(InputValue $inputValue) : object
    {
        $return = new ($inputValue->getType()->getDataClass())();

        foreach ($inputValue as $argumentName => $argumentValue) {
            // use separate hydrator?
            $return->{$argumentName} = $argumentValue->getValue()->accept($this); // @phpstan-ignore property.dynamicName
        }

        return $return;
    }

    #[\Override]
    public function visitVariable(VariableValue $variableValue) : mixed
    {
        return $variableValue->getConcreteValue()->accept($this);
    }
}
