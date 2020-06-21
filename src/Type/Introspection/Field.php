<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Field extends \Graphpinator\Type\Type
{
    protected const NAME = '__Field';
    protected const DESCRIPTION = 'Built-in introspection type.';

    private \Graphpinator\Type\Container\Container $container;

    public function __construct(\Graphpinator\Type\Container\Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Field\Field;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Type\Container\Container::String()->notNull(),
                static function (\Graphpinator\Field\Field $field) : string {
                    return $field->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Field\Field $field) : ?string {
                    return $field->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'args',
                $this->container->introspectionInputValue()->notNullList(),
                static function (\Graphpinator\Field\Field $field) : \Graphpinator\Argument\ArgumentSet {
                    return $field->getArguments();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'type',
                $this->container->introspectionType()->notNull(),
                static function (\Graphpinator\Field\Field $field) : \Graphpinator\Type\Contract\Definition {
                    return $field->getType();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'isDeprecated',
                \Graphpinator\Type\Container\Container::Boolean()->notNull(),
                static function (\Graphpinator\Field\Field $field) : bool {
                    return $field->isDeprecated();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'deprecationReason',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Field\Field $field) : ?string {
                    return $field->getDeprecationReason();
                },
            ),
        ]);
    }
}
