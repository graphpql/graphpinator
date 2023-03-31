<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\EnumItem\EnumItem;
use \Graphpinator\Typesystem\Field\ResolvableField;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class EnumValue extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__EnumValue';

    public function __construct()
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof EnumItem;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            ResolvableField::create(
                'name',
                Container::String()->notNull(),
                static function (EnumItem $item) : string {
                    return $item->getName();
                },
            ),
            ResolvableField::create(
                'description',
                Container::String(),
                static function (EnumItem $item) : ?string {
                    return $item->getDescription();
                },
            ),
            ResolvableField::create(
                'isDeprecated',
                Container::Boolean()->notNull(),
                static function (EnumItem $item) : bool {
                    return $item->isDeprecated();
                },
            ),
            ResolvableField::create(
                'deprecationReason',
                Container::String(),
                static function (EnumItem $item) : ?string {
                    return $item->getDeprecationReason();
                },
            ),
        ]);
    }
}
