<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Where;

abstract class BaseWhereDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\ExecutableDefinition
{
    use \Graphpinator\Directive\Contract\TExecutableDefinition;

    protected const TYPE = '';
    protected const TYPE_NAME = '';

    public function validateType(
        ?\Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        return $definition instanceof \Graphpinator\Type\Contract\Definition
            ? $definition->getShapingType() instanceof \Graphpinator\Type\ListType
            : false;
    }

    protected static function extractValue(\Graphpinator\Value\ResolvedValue $singleValue, ?string $where) : string|int|float|bool|array|null
    {
        $whereArr = \is_string($where)
            ? \array_reverse(\explode('.', $where))
            : [];

        $resolvedValue = static::extractValueImpl($singleValue, $whereArr);

        if ($resolvedValue instanceof \Graphpinator\Value\NullResolvedValue || $resolvedValue->getType() instanceof (static::TYPE)) {
            return $resolvedValue->getRawValue();
        }

        throw new \Graphpinator\Exception\Directive\InvalidValueType(static::NAME, static::TYPE_NAME, $resolvedValue);
    }

    protected static function extractValueImpl(\Graphpinator\Value\ResolvedValue $singleValue, array& $where) : \Graphpinator\Value\ResolvedValue
    {
        if (\count($where) === 0) {
            return $singleValue;
        }

        $currentWhere = \array_pop($where);

        if (\is_numeric($currentWhere)) {
            $currentWhere = (int) $currentWhere;

            if (!$singleValue instanceof \Graphpinator\Value\ListValue) {
                throw new \Graphpinator\Exception\Directive\ExpectedListValue($currentWhere, $singleValue);
            }

            if (!$singleValue->offsetExists($currentWhere)) {
                throw new \Graphpinator\Exception\Directive\InvalidListOffset($currentWhere);
            }

            return static::extractValueImpl($singleValue->offsetGet($currentWhere), $where);
        }

        if (!$singleValue instanceof \Graphpinator\Value\TypeValue) {
            throw new \Graphpinator\Exception\Directive\ExpectedTypeValue($currentWhere, $singleValue);
        }

        if (!isset($singleValue->{$currentWhere})) {
            throw new \Graphpinator\Exception\Directive\InvalidFieldOffset($currentWhere, $singleValue);
        }

        return static::extractValueImpl($singleValue->{$currentWhere}->getValue(), $where);
    }
}
