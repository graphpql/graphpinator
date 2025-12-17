<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Introspection;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;

#[Description('Built-in introspection type')]
final class EnumValue extends Type
{
    protected const NAME = '__EnumValue';

    public function __construct()
    {
        parent::__construct();
    }

    #[\Override]
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof EnumItem;
    }

    #[\Override]
    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
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
