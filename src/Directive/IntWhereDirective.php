<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class IntWhereDirective extends \Graphpinator\Directive\BaseWhereDirective
{
    protected const NAME = 'intWhere';
    protected const DESCRIPTION = 'Graphpinator intWhere directive.';
    protected const TYPE = \Graphpinator\Type\Scalar\IntType::class;
    protected const TYPE_NAME = 'Int';

    public function __construct()
    {
        parent::__construct(
            [
                ExecutableDirectiveLocation::FIELD,
            ],
            true,
            new \Graphpinator\Argument\ArgumentSet([
                \Graphpinator\Argument\Argument::create('field', \Graphpinator\Container\Container::String()),
                \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
                \Graphpinator\Argument\Argument::create('equals', \Graphpinator\Container\Container::Int()),
                \Graphpinator\Argument\Argument::create('greaterThan', \Graphpinator\Container\Container::Int()),
                \Graphpinator\Argument\Argument::create('lessThan', \Graphpinator\Container\Container::Int()),
                \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ]),
        );

        $this->fieldAfterFn = static function (
            \Graphpinator\Value\ListResolvedValue $value,
            ?string $field,
            bool $not,
            ?int $equals,
            ?int $greaterThan,
            ?int $lessThan,
            bool $orNull,
        ) : string {
            foreach ($value as $key => $item) {
                $singleValue = self::extractValue($item, $field);
                $condition = self::satisfiesCondition($singleValue, $equals, $greaterThan, $lessThan, $orNull);

                if ($condition === $not) {
                    unset($value[$key]);
                }
            }

            return FieldDirectiveResult::NONE;
        };
    }

    private static function satisfiesCondition(?int $value, ?int $equals, ?int $greaterThan, ?int $lessThan, bool $orNull) : bool
    {
        if ($value === null) {
            return $orNull;
        }

        if (\is_int($equals) && $value !== $equals) {
            return false;
        }

        if (\is_int($greaterThan) && $value < $greaterThan) {
            return false;
        }

        if (\is_int($lessThan) && $value > $lessThan) {
            return false;
        }

        return true;
    }
}
