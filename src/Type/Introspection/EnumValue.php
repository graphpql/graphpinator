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

    public function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Type\Scalar\EnumItem;
    }

    protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Type\Container\Container::String()->notNull(),
                static function (\Graphpinator\Type\Scalar\EnumItem $item) : string {
                    return $item->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Type\Scalar\EnumItem $item) : ?string {
                    return $item->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'isDeprecated',
                \Graphpinator\Type\Container\Container::Boolean()->notNull(),
                static function (\Graphpinator\Type\Scalar\EnumItem $item) : bool {
                    return $item->isDeprecated();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'deprecationReason',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Type\Scalar\EnumItem $item) : ?string {
                    return $item->getDeprecationReason();
                },
            ),
        ]);
    }
}