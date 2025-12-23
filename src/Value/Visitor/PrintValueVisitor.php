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
 * @implements InputedValueVisitor<string>
 */
final readonly class PrintValueVisitor implements InputedValueVisitor
{
    #[\Override]
    public function visitNull(NullValue $nullValue) : string
    {
        return 'null';
    }

    #[\Override]
    public function visitList(ListValue $listValue) : string
    {
        $component = [];

        foreach ($listValue as $value) {
            \assert($value instanceof InputedValue);

            $component[] = $value->accept($this);
        }

        return '[' . \implode(',', $component) . ']';
    }

    #[\Override]
    public function visitScalar(ScalarValue $scalarValue) : string
    {
        return \json_encode($scalarValue->jsonSerialize(), \JSON_THROW_ON_ERROR |
            \JSON_UNESCAPED_UNICODE |
            \JSON_UNESCAPED_SLASHES |
            \JSON_PRESERVE_ZERO_FRACTION);
    }

    #[\Override]
    public function visitEnum(EnumValue $enumValue) : string
    {
        return $enumValue->jsonSerialize();
    }

    #[\Override]
    public function visitInput(InputValue $inputValue) : string
    {
        $component = [];

        foreach ($inputValue as $argumentName => $argumentValue) {
            $component[] = $argumentName . ':' . $argumentValue->getValue()->accept($this);
        }

        return '{' . \implode(',', $component) . '}';
    }

    #[\Override]
    public function visitVariable(VariableValue $variableValue) : never
    {
        throw new \LogicException('Not implemented'); // @codeCoverageIgnore
    }
}
