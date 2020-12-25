<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class BooleanWhereDirective extends \Graphpinator\Directive\BaseWhereDirective
{
    protected const NAME = 'booleanWhere';
    protected const DESCRIPTION = 'Graphpinator booleanWhere directive.';
    protected const TYPE = \Graphpinator\Type\Scalar\BooleanType::class;
    protected const TYPE_NAME = 'Boolean';

    public function __construct()
    {
        parent::__construct(
            [
                ExecutableDirectiveLocation::FIELD,
            ],
            true,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('field', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('equals', \Graphpinator\Container\Container::Boolean()),
            ]),
            null,
            static function (\Graphpinator\Value\ListResolvedValue $value, ?string $field, ?bool $equals) : string {
                foreach ($value as $key => $item) {
                    $singleValue = self::extractValue($item, $field);
                    $condition = self::satisfiesCondition($singleValue, $equals);

                    if (!$condition) {
                        unset($value[$key]);
                    }
                }

                return DirectiveResult::NONE;
            },
        );
    }

    private static function satisfiesCondition(bool $value, ?bool $equals) : bool
    {
        return !\is_bool($equals) || $value === $equals;
    }
}
