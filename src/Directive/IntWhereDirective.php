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
                new \Graphpinator\Argument\Argument('field', \Graphpinator\Container\Container::String()),
                \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())->setDefaultValue(false),
                new \Graphpinator\Argument\Argument('equals', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('greaterThan', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('lessThan', \Graphpinator\Container\Container::Int()),
            ]),
            null,
            static function (\Graphpinator\Value\ListResolvedValue $value, ?string $field, bool $not, ?int $equals, ?int $greaterThan, ?int $lessThan) : string {
                foreach ($value as $key => $item) {
                    $singleValue = self::extractValue($item, $field);
                    $condition = self::satisfiesCondition($singleValue, $equals, $greaterThan, $lessThan);

                    if ($condition === $not) {
                        unset($value[$key]);
                    }
                }

                return DirectiveResult::NONE;
            },
        );
    }

    private static function satisfiesCondition(int $value, ?int $equals, ?int $greaterThan, ?int $lessThan) : bool
    {
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
