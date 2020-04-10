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

    protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Type\Container\Container::String()->notNull(),
                static function (string $item) : string {
                    return $item;
                },
            ),
        ]);
    }
}
