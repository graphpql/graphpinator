<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Value\Exception\InvalidValue;

final readonly class EnumValue implements InputedValue, OutputValue
{
    private string|\BackedEnum $rawValue;

    public function __construct(
        private EnumType $type,
        mixed $rawValue,
        bool $inputed,
    )
    {
        $this->rawValue = self::coerceInput($type, $rawValue, $inputed);
    }

    #[\Override]
    public function getRawValue(bool $forResolvers = false) : string|\BackedEnum
    {
        return $this->rawValue;
    }

    #[\Override]
    public function getType() : EnumType
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : string
    {
        return self::coerceOutput($this->rawValue);
    }

    #[\Override]
    public function printValue() : string
    {
        return self::coerceOutput($this->rawValue);
    }

    #[\Override]
    public function applyVariables(VariableValueSet $variables) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveRemainingDirectives() : void
    {
        // nothing here
    }

    #[\Override]
    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self
            && $this->rawValue === $compare->getRawValue();
    }

    private static function coerceInput(EnumType $type, mixed $rawValue, bool $inputed) : string|\BackedEnum
    {
        $enumClass = $type->getEnumClass();

        if (\is_string($rawValue) && $type->getItems()->offsetExists($rawValue)) {
            return \is_string($enumClass)
                ? \call_user_func([$enumClass, 'from'], $rawValue) // value should be a native enum
                : $rawValue; // value is correctly string
        }

        if ($rawValue instanceof \BackedEnum && $type->getItems()->offsetExists($rawValue->value)) {
            if (\is_string($enumClass)) {
                return $rawValue::class === $enumClass
                    ? $rawValue // value is correctly native enum
                    : \call_user_func([$enumClass, 'from'], $rawValue->value); // value is enum of different type -> convert
            }

            return $rawValue->value; // value should be string
        }

        throw new InvalidValue($type, $rawValue, $inputed);
    }

    private static function coerceOutput(string|\BackedEnum $rawValue) : string
    {
        return $rawValue instanceof \BackedEnum
            ? $rawValue->value
            : $rawValue;
    }
}
