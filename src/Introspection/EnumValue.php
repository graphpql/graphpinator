<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\EnumItem\EnumItem;
use \Graphpinator\Typesystem\Field\ResolvableField;

final class EnumValue extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__EnumValue';
    protected const DESCRIPTION = 'Built-in introspection type.';

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
            new ResolvableField(
                'name',
                Container::String()->notNull(),
                static function (EnumItem $item) : string {
                    return $item->getName();
                },
            ),
            new ResolvableField(
                'description',
                Container::String(),
                static function (EnumItem $item) : ?string {
                    return $item->getDescription();
                },
            ),
            new ResolvableField(
                'isDeprecated',
                Container::Boolean()->notNull(),
                static function (EnumItem $item) : bool {
                    return $item->isDeprecated();
                },
            ),
            new ResolvableField(
                'deprecationReason',
                Container::String(),
                static function (EnumItem $item) : ?string {
                    return $item->getDeprecationReason();
                },
            ),
        ]);
    }
}
