<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Where;

final class ListWhereDirective extends \Graphpinator\Directive\Where\BaseWhereDirective
{
    protected const NAME = 'listWhere';
    protected const DESCRIPTION = 'Graphpinator listWhere directive.';
    protected const TYPE = \Graphpinator\Type\ListType::class;
    protected const TYPE_NAME = 'List';

    public function __construct(
        private \Graphpinator\Directive\Constraint\IntConstraintDirective $intConstraintDirective,
    )
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
            ?int $minItems,
            ?int $maxItems,
            bool $orNull,
        ) : string {
            foreach ($value as $key => $item) {
                $singleValue = self::extractValue($item, $field);
                $condition = self::satisfiesCondition($singleValue, $minItems, $maxItems, $orNull);

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
            \Graphpinator\Argument\Argument::create('minItems', \Graphpinator\Container\Container::Int())
                ->addDirective(
                    $this->intConstraintDirective,
                    ['min' => 0],
                ),
            \Graphpinator\Argument\Argument::create('maxItems', \Graphpinator\Container\Container::Int())
                ->addDirective(
                    $this->intConstraintDirective,
                    ['min' => 0],
                ),
            \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }

    private static function satisfiesCondition(?array $value, ?int $minItems, ?int $maxItems, bool $orNull) : bool
    {
        if ($value === null) {
            return $orNull;
        }

        if (\is_int($minItems) && \count($value) < $minItems) {
            return false;
        }

        if (\is_int($maxItems) && \count($value) > $maxItems) {
            return false;
        }

        return true;
    }
}
