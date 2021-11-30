<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type.')]
final class EnumValue extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__EnumValue';

    public function __construct()
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Typesystem\EnumItem\EnumItem;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'name',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                static function (\Graphpinator\Typesystem\EnumItem\EnumItem $item) : string {
                    return $item->getName();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\EnumItem\EnumItem $item) : ?string {
                    return $item->getDescription();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'isDeprecated',
                \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                static function (\Graphpinator\Typesystem\EnumItem\EnumItem $item) : bool {
                    return $item->isDeprecated();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'deprecationReason',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\EnumItem\EnumItem $item) : ?string {
                    return $item->getDeprecationReason();
                },
            ),
        ]);
    }
}
