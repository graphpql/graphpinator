<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Where;

final class StringWhereDirective extends \Graphpinator\Directive\Where\BaseWhereDirective
{
    protected const NAME = 'stringWhere';
    protected const DESCRIPTION = 'Graphpinator stringWhere directive.';
    protected const TYPE = \Graphpinator\Type\Scalar\StringType::class;
    protected const TYPE_NAME = 'String';

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

            return \Graphpinator\Directive\FieldDirectiveResult::NONE;
        };
    }

    protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('field', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Argument\Argument::create('equals', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('contains', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('startsWith', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('endsWith', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }

    private static function satisfiesCondition(
        ?string $value,
        ?string $equals,
        ?string $contains,
        ?string $startsWith,
        ?string $endsWith,
        bool $orNull,
    ) : bool
    {
        if ($value === null) {
            return $orNull;
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
