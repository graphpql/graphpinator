<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Where;

final class FloatWhereDirective extends \Graphpinator\Directive\Where\BaseWhereDirective
{
    protected const NAME = 'floatWhere';
    protected const DESCRIPTION = 'Graphpinator floatWhere directive.';
    protected const TYPE = \Graphpinator\Type\Scalar\FloatType::class;
    protected const TYPE_NAME = 'Float';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD,
            ],
            true,
        );

        $this->fieldAfterFn = static function (
            \Graphpinator\Value\ListResolvedValue $value,
            ?string $field,
            bool $not,
            ?float $equals,
            ?float $greaterThan,
            ?float $lessThan,
            bool $orNull,
        ) : string {
            foreach ($value as $key => $item) {
                $singleValue = self::extractValue($item, $field);
                $condition = self::satisfiesCondition($singleValue, $equals, $greaterThan, $lessThan, $orNull);

                if ($condition === $not) {
                    unset($value[$key]);
                }
            }

            return \Graphpinator\Directive\FieldDirectiveResult::NONE;
        };
    }

    protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('field', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Argument\Argument::create('equals', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('greaterThan', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('lessThan', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }

    private static function satisfiesCondition(?float $value, ?float $equals, ?float $greaterThan, ?float $lessThan, bool $orNull) : bool
    {
        if ($value === null) {
            return $orNull;
        }

        if (\is_float($equals) && $value !== $equals) {
            return false;
        }

        if (\is_float($greaterThan) && $value < $greaterThan) {
            return false;
        }

        if (\is_float($lessThan) && $value > $lessThan) {
            return false;
        }

        return true;
    }
}
