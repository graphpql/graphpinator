<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class BaseWhereDirective extends \Graphpinator\Directive\ExecutableDirective
{
    protected static function extractValue(\Graphpinator\Value\ResolvedValue $singleValue, ?string $where, string $expectedType) : mixed
    {
        $whereArr = \is_string($where)
            ? \array_reverse(\explode('.', $where))
            : [];

        return static::extractValueImpl($singleValue, $whereArr, $expectedType)->getRawValue();
    }

    protected static function extractValueImpl(\Graphpinator\Value\ResolvedValue $singleValue, array& $value, string $expectedType) : \Graphpinator\Value\ResolvedValue
    {
        if (\count($value) === 0) {
            if (!$singleValue->getType() instanceof $expectedType) {
                throw new \Graphpinator\Exception\Directive\InvalidValueType();
            }

            return $singleValue;
        }

        $where = \array_pop($value);

        if (\is_numeric($where)) {
            $where = (int) $where;

            if (!$singleValue instanceof \Graphpinator\Value\ListValue) {
                throw new \Graphpinator\Exception\Directive\ExpectedListValue(\get_class($singleValue));
            }

            if (!$singleValue->offsetExists($where)) {
                throw new \Graphpinator\Exception\Directive\InvalidListOffset();
            }

            return static::extractValueImpl($singleValue[$where], $value, $expectedType);
        }

        if (!$singleValue instanceof \Graphpinator\Value\TypeValue) {
            throw new \Graphpinator\Exception\Directive\ExpectedTypeValue(\get_class($singleValue));
        }

        if (!isset($singleValue->{$where})) {
            throw new \Graphpinator\Exception\Directive\InvalidFieldOffset();
        }

        return static::extractValueImpl($singleValue->{$where}->getValue(), $value, $expectedType);
    }
}
