<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

final class InputValue extends \Graphpinator\Type\Type
{
    protected const NAME = '__InputValue';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private \Graphpinator\Container\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Argument\Argument;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Container\Container::String()->notNull(),
                static function (\Graphpinator\Argument\Argument $argument) : string {
                    return $argument->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Argument\Argument $argument) : ?string {
                    return $argument->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (\Graphpinator\Argument\Argument $argument) : \Graphpinator\Type\Contract\Definition {
                    return $argument->getType();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'defaultValue',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Argument\Argument $argument) : ?string {
                    return $argument->getDefaultValue() instanceof \Graphpinator\Value\ArgumentValue
                        ? $argument->getDefaultValue()->getValue()->printValue()
                        : null;
                },
            ),
        ]);
    }
}
