<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class EnumValue extends \Graphpinator\Type\Type
{
    protected const NAME = '__EnumValue';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct()
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Type\Enum\EnumItem;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Container\Container::String()->notNull(),
                static function (\Graphpinator\Type\Enum\EnumItem $item) : string {
                    return $item->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Type\Enum\EnumItem $item) : ?string {
                    return $item->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'isDeprecated',
                \Graphpinator\Container\Container::Boolean()->notNull(),
                static function (\Graphpinator\Type\Enum\EnumItem $item) : bool {
                    return $item->isDeprecated();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'deprecationReason',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Type\Enum\EnumItem $item) : ?string {
                    return $item->getDeprecationReason();
                },
            ),
        ]);
    }
}
