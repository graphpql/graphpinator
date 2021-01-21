<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ListInputedValue extends \Graphpinator\Value\ListValue implements \Graphpinator\Value\InputedValue
{
    private function __construct(\Graphpinator\Type\ListType $type, array $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public static function fromRaw(
        \Graphpinator\Type\ListType $type,
        array $rawValue
    ) : self
    {
        $innerType = $type->getInnerType();
        \assert($innerType instanceof \Graphpinator\Type\Contract\Inputable);

        $inner = [];

        foreach ($rawValue as $item) {
            $inner[] = $innerType->createInputedValue($item);
        }

        return new self($type, $inner);
    }

    public static function fromParsed(
        \Graphpinator\Type\ListType $type,
        \Graphpinator\Parser\Value\ListVal $parsed,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : self
    {
        $inner = [];

        foreach ($parsed->getValue() as $parserValue) {
            \assert($parserValue instanceof \Graphpinator\Parser\Value\Value);

            $inner[] = $parserValue->createInputedValue($type->getInnerType(), $variableSet);
        }

        return new self($type, $inner);
    }

    public function getRawValue(bool $forResolvers = false) : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            \assert($listItem instanceof InputedValue);

            $return[] = $listItem->getRawValue($forResolvers);
        }

        return $return;
    }

    public function getType() : \Graphpinator\Type\ListType
    {
        return $this->type;
    }

    public function printValue() : string
    {
        $component = [];

        foreach ($this->value as $value) {
            \assert($value instanceof InputedValue);

            $component[] = $value->printValue();
        }

        return '[' . \implode(',', $component) . ']';
    }

    public function prettyPrint(int $indentLevel) : string
    {
        if (\count($this->value) === 0) {
            return '[]';
        }

        $component = [];
        $indent = \str_repeat('  ', $indentLevel);
        $innerIndent = $indent . '  ';

        foreach ($this->value as $value) {
            \assert($value instanceof InputedValue);

            $component[] = $value->prettyPrint($indentLevel + 1);
        }

        return '[' . \PHP_EOL . $innerIndent . \implode(',' . \PHP_EOL . $innerIndent, $component) . \PHP_EOL . $indent . ']';
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        foreach ($this->value as $value) {
            \assert($value instanceof InputedValue);

            $value->applyVariables($variables);
        }
    }

    public function isSame(Value $compare) : bool
    {
        if (!$compare instanceof self) {
            return false;
        }

        $secondArray = $compare->value;

        if (\count($secondArray) !== \count($this->value)) {
            return false;
        }

        foreach ($this->value as $key => $value) {
            \assert($value instanceof InputedValue);

            if (!\array_key_exists($key, $secondArray) || !$value->isSame($secondArray[$key])) {
                return false;
            }
        }

        return true;
    }
}
