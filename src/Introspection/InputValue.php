<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type.')]
final class InputValue extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__InputValue';

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Typesystem\Argument\Argument;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'name',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                static function (\Graphpinator\Typesystem\Argument\Argument $argument) : string {
                    return $argument->getName();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Argument\Argument $argument) : ?string {
                    return $argument->getDescription();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (\Graphpinator\Typesystem\Argument\Argument $argument) : \Graphpinator\Typesystem\Contract\Type {
                    return $argument->getType();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'defaultValue',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Argument\Argument $argument) : ?string {
                    return $argument->getDefaultValue() instanceof \Graphpinator\Value\ArgumentValue
                        ? $argument->getDefaultValue()->getValue()->printValue()
                        : null;
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'isDeprecated',
                \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                static function (\Graphpinator\Typesystem\Argument\Argument $argument) : bool {
                    return $argument->isDeprecated();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'deprecationReason',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Argument\Argument $argument) : ?string {
                    return $argument->getDeprecationReason();
                },
            ),
        ]);
    }
}
