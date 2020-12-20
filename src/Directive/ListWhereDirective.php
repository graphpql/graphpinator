<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class ListWhereDirective extends \Graphpinator\Directive\BaseWhereDirective
{
    protected const NAME = 'listWhere';
    protected const DESCRIPTION = 'Graphpinator listWhere directive.';

    public function __construct()
    {
        parent::__construct(
            [
                ExecutableDirectiveLocation::FIELD,
            ],
            true,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('field', \Graphpinator\Container\Container::String()),
                \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())->setDefaultValue(false),
                new \Graphpinator\Argument\Argument('minItems', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('maxItems', \Graphpinator\Container\Container::Int()),
            ]),
            null,
            static function (\Graphpinator\Value\ListResolvedValue $value, ?string $field, bool $not, ?int $minItems, ?int $maxItems) : string {
                foreach ($value as $key => $item) {
                    $singleValue = self::extractValue($item, $field, \Graphpinator\Type\ListType::class);
                    $condition = self::satisfiesCondition($singleValue, $minItems, $maxItems);

                    if ($condition === $not) {
                        unset($value[$key]);
                    }
                }

                return DirectiveResult::NONE;
            },
        );
    }

    private static function satisfiesCondition(array $value, ?int $minItems, ?int $maxItems) : bool
    {
        if (\is_int($minItems) && \count($value) < $minItems) {
            return false;
        }

        if (\is_int($maxItems) && \count($value) > $maxItems) {
            return false;
        }

        return true;
    }

    /*private static function extractValue(\Graphpinator\Value\ResolvedValue $singleValue, ?string $where) : array
    {
        $whereArr = \is_string($where)
            ? \array_reverse(\explode('.', $where))
            : [];

        return static::extractValueImpl($singleValue, $whereArr)->getRawValue();
    }

    private static function extractValueImpl(\Graphpinator\Value\ResolvedValue $singleValue, array& $value) : \Graphpinator\Value\ResolvedValue
    {
        if (\count($value) === 0) {
            if (!$singleValue->getType() instanceof \Graphpinator\Type\ListType) {
                throw new \Exception('Value has invalid type');
            }

            return $singleValue;
        }

        $where = \array_pop($value);

        if (\is_numeric($where)) {
            $where = (int) $where;

            if (!$singleValue instanceof \Graphpinator\Value\ListValue) {
                throw new \Exception('Invalid Resolved value');
            }

            if (!$singleValue->offsetExists($where)) {
                throw new \Exception('Invalid list offset');
            }

            return static::extractValueImpl($singleValue[$where], $value);
        }

        if (!$singleValue instanceof \Graphpinator\Value\TypeValue) {
            throw new \Exception('Invalid Resolved value');
        }

        if (!isset($singleValue->{$where})) {
            throw new \Exception('Invalid field offset');
        }

        return static::extractValueImpl($singleValue->{$where}->getValue(), $value);
    }*/
}
