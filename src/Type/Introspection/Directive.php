<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Directive extends \Graphpinator\Type\Type
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validateNonNullValue($rawValue): void
    {
    }

    protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Type\Container\Container::String()->notNull(),
                static function () : string {
                    return '';
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function () : ?string {
                    return null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'locations',
                \Graphpinator\Type\Container\Container::introspectionDirectiveLocation()->notNullList(),
                static function () : array {
                    return [];
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'args',
                \Graphpinator\Type\Container\Container::introspectionInputValue()->notNullList(),
                static function () : \Graphpinator\Argument\ArgumentSet {
                    return null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'isRepeatable',
                \Graphpinator\Type\Container\Container::Boolean()->notNull(),
                static function () : bool {
                    return false;
                },
            ),
        ]);
    }
}
