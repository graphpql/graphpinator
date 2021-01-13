<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class StringWhereDirective extends \Graphpinator\Directive\BaseWhereDirective
{
    protected const NAME = 'stringWhere';
    protected const DESCRIPTION = 'Graphpinator stringWhere directive.';
    protected const TYPE = \Graphpinator\Type\Scalar\StringType::class;
    protected const TYPE_NAME = 'String';

    public function __construct()
    {
        parent::__construct(
            [
                ExecutableDirectiveLocation::FIELD,
            ],
            true,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('field', \Graphpinator\Container\Container::String()),
                \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
                new \Graphpinator\Argument\Argument('equals', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('contains', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('startsWith', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('endsWith', \Graphpinator\Container\Container::String()),
                \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ]),
            null,
            static function (
                \Graphpinator\Value\ListResolvedValue $value,
                ?string $field,
                bool $not,
                ?string $equals,
                ?string $contains,
                ?string $startsWith,
                ?string $endsWith,
                bool $orNull,
            ) : string {
                foreach ($value as $key => $item) {
                    $singleValue = self::extractValue($item, $field);
                    $condition = self::satisfiesCondition($singleValue, $equals, $contains, $startsWith, $endsWith, $orNull);

                    if ($condition === $not) {
                        unset($value[$key]);
                    }
                }

                return DirectiveResult::NONE;
            },
        );
    }

    public function validateType(\Graphpinator\Type\Contract\Outputable $type) : bool
    {
        return $type instanceof \Graphpinator\Type\ListType
            && $type->getInnerType() instanceof \Graphpinator\Type\Scalar\StringType;
    }

    private static function satisfiesCondition(?string $value, ?string $equals, ?string $contains, ?string $startsWith, ?string $endsWith, bool $orNull) : bool
    {
        if ($value === null) {
            return $orNull === true;
        }

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
}
