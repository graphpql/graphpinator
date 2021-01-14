<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class ListWhereDirective extends \Graphpinator\Directive\BaseWhereDirective
{
    protected const NAME = 'listWhere';
    protected const DESCRIPTION = 'Graphpinator listWhere directive.';
    protected const TYPE = \Graphpinator\Type\ListType::class;
    protected const TYPE_NAME = 'List';

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
                \Graphpinator\Argument\Argument::create('minItems', \Graphpinator\Container\Container::Int()),
                \Graphpinator\Argument\Argument::create('maxItems', \Graphpinator\Container\Container::Int()),
                \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ]),
            null,
            static function (
                \Graphpinator\Value\ListResolvedValue $value, ?string $field, bool $not, ?int $minItems, ?int $maxItems, bool $orNull,
            ) : string {
                foreach ($value as $key => $item) {
                    $singleValue = self::extractValue($item, $field);
                    $condition = self::satisfiesCondition($singleValue, $minItems, $maxItems, $orNull);

                    if ($condition === $not) {
                        unset($value[$key]);
                    }
                }

                return DirectiveResult::NONE;
            },
        );
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
