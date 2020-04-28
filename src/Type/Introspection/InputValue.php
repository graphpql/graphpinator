<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class InputValue extends \Graphpinator\Type\Type
{
    protected const NAME = '__InputValue';
    protected const DESCRIPTION = 'Built-in introspection type.';

    private \Graphpinator\Type\Container\Container $container;

    public function __construct(\Graphpinator\Type\Container\Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Argument\Argument;
    }

    protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Type\Container\Container::String()->notNull(),
                static function (\Graphpinator\Argument\Argument $argument) : string {
                    return $argument->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Argument\Argument $argument) : ?string {
                    return $argument->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'type',
                $this->container->introspectionType()->notNull(),
                static function (\Graphpinator\Argument\Argument $argument) : \Graphpinator\Type\Contract\Definition {
                    return $argument->getType();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'defaultValue',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Argument\Argument $argument) : ?string {
                    return $argument->getDefaultValue() instanceof \Graphpinator\Resolver\Value\ValidatedValue
                        ? $argument->getDefaultValue()->jsonSerialize()
                        : null;
                },
            ),
        ]);
    }
}
