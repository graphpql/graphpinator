<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Visitor;

use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Contract\InputedValueVisitor;
use Graphpinator\Value\Contract\Value;
use Graphpinator\Value\EnumValue;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\ListValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\VariableValue;

/**
 * @implements InputedValueVisitor<bool>
 */
final readonly class IsValueSameVisitor implements InputedValueVisitor
{
    public function __construct(
        private Value $compare,
    )
    {
    }

    #[\Override]
    public function visitNull(NullValue $nullValue) : bool
    {
        return $this->compare instanceof NullValue;
    }

    #[\Override]
    public function visitList(ListValue $listValue) : bool
    {
        if (!$this->compare instanceof ListValue) {
            return false;
        }

        $firstArray = $listValue->value;
        $secondArray = $this->compare->value;

        if (\count($firstArray) !== \count($secondArray)) {
            return false;
        }

        foreach ($firstArray as $key => $value) {
            \assert($value instanceof InputedValue);

            if (!\array_key_exists($key, $secondArray) || !$value->accept(new self($secondArray[$key]))) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function visitScalar(ScalarValue $scalarValue) : bool
    {
        return $this->compare instanceof ScalarValue
            && $this->compare->getRawValue() === $scalarValue->getRawValue();
    }

    #[\Override]
    public function visitEnum(EnumValue $enumValue) : bool
    {
        return $this->compare instanceof EnumValue
            && $this->compare->getRawValue() === $enumValue->getRawValue();
    }

    #[\Override]
    public function visitInput(InputValue $inputValue) : bool
    {
        if (!$this->compare instanceof InputValue) {
            return false;
        }

        $firstObject = $inputValue->value;
        $secondObject = $this->compare->value;

        if (\count((array) $firstObject) !== \count((array) $secondObject)) {
            return false;
        }

        foreach ((array) $firstObject as $argumentName => $argumentValue) {
            \assert($argumentValue instanceof ArgumentValue);

            if (!\property_exists($secondObject, $argumentName) ||
                !$argumentValue->value->accept(new self($secondObject->{$argumentName}->value))) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function visitVariable(VariableValue $variableValue) : bool
    {
        return $this->compare instanceof VariableValue
            && $this->compare->variable->name === $variableValue->variable->name;
    }
}
