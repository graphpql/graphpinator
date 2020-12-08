<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class StringWhereDirective extends \Graphpinator\Directive\ExecutableDirective
{
    protected const NAME = 'stringWhere';
    protected const DESCRIPTION = 'Graphpinator stringWhere directive.';

    public function __construct()
    {
        parent::__construct(
            [
                ExecutableDirectiveLocation::FIELD,
            ],
            true,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('field', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('not', \Graphpinator\Container\Container::Boolean()->notNull(), false),
                new \Graphpinator\Argument\Argument('equals', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('contains', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('startsWith', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('endsWith', \Graphpinator\Container\Container::String()),
            ]),
            null,
            static function (\Graphpinator\Value\ListResolvedValue $value, ?string $field, bool $not, ?string $equals, ?string $contains, ?string $startsWith, ?string $endsWith) : string {
                foreach ($value as $key => $item) {
                    $singleValue = self::extractValue($item, $field)->getRawValue();
                    $condition = self::satisfiesCondition($singleValue, $equals, $contains, $startsWith, $endsWith);

                    if ($condition === $not) {
                        unset($value[$key]);
                    }
                }

                return DirectiveResult::NONE;
            },
        );
    }

    private static function satisfiesCondition(string $value, ?string $equals, ?string $contains, ?string $startsWith, ?string $endsWith) : bool
    {
        if (\is_string($equals) && $value !== $equals) {
            return false;
        }

        if (\is_string($contains) && !\str_contains($value, $contains)) {
            return false;
        }

        if (\is_string($startsWith) && !\str_starts_with($value, $startsWith)) {
            return false;
        }

        if (\is_string($endsWith) && !\str_ends_with($value, $endsWith)) {
            return false;
        }

        return true;
    }

    private static function extractValue(\Graphpinator\Value\ResolvedValue $singleValue, ?string $where) : \Graphpinator\Value\ResolvedValue
    {
        if ($where === null) {
            return $singleValue;
        }
    }
}
