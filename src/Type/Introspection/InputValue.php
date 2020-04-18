<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class InputValue extends \Graphpinator\Type\Type
{
    protected const NAME = '__InputValue';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct()
    {
        parent::__construct();
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
                \Graphpinator\Type\Container\Container::introspectionType()->notNull(),
                static function (\Graphpinator\Argument\Argument $argument) : \Graphpinator\Type\Contract\Definition {
                    return $argument->getType();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'defaultValue',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Argument\Argument $argument) : ?string {
                    return $argument->getDefaultValue() instanceof \Graphpinator\Value\ValidatedValue
                        ? \json_encode($argument->getDefaultValue(), \JSON_THROW_ON_ERROR, 512)
                        : null;
                },
            ),
        ]);
    }
}
