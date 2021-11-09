<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Argument\Argument;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

final class InputValue extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__InputValue';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof Argument;
    }

    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            new ResolvableField(
                'name',
                Container::String()->notNull(),
                static function (Argument $argument) : string {
                    return $argument->getName();
                },
            ),
            new ResolvableField(
                'description',
                Container::String(),
                static function (Argument $argument) : ?string {
                    return $argument->getDescription();
                },
            ),
            new ResolvableField(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (Argument $argument) : \Graphpinator\Typesystem\Contract\Type {
                    return $argument->getType();
                },
            ),
            new ResolvableField(
                'defaultValue',
                Container::String(),
                static function (Argument $argument) : ?string {
                    return $argument->getDefaultValue() instanceof \Graphpinator\Value\ArgumentValue
                        ? $argument->getDefaultValue()->getValue()->printValue()
                        : null;
                },
            ),
            new ResolvableField(
                'isDeprecated',
                Container::Boolean()->notNull(),
                static function (Argument $argument) : bool {
                    return $argument->isDeprecated();
                },
            ),
            new ResolvableField(
                'deprecationReason',
                Container::String(),
                static function (Argument $argument) : ?string {
                    return $argument->getDeprecationReason();
                },
            ),
        ]);
    }
}
