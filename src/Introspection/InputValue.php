<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Argument\Argument;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Field\ResolvableField;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class InputValue extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__InputValue';

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

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            ResolvableField::create(
                'name',
                Container::String()->notNull(),
                static function (Argument $argument) : string {
                    return $argument->getName();
                },
            ),
            ResolvableField::create(
                'description',
                Container::String(),
                static function (Argument $argument) : ?string {
                    return $argument->getDescription();
                },
            ),
            ResolvableField::create(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (Argument $argument) : \Graphpinator\Typesystem\Contract\Type {
                    return $argument->getType();
                },
            ),
            ResolvableField::create(
                'defaultValue',
                Container::String(),
                static function (Argument $argument) : ?string {
                    return $argument->getDefaultValue() instanceof \Graphpinator\Value\ArgumentValue
                        ? $argument->getDefaultValue()->getValue()->printValue()
                        : null;
                },
            ),
            ResolvableField::create(
                'isDeprecated',
                Container::Boolean()->notNull(),
                static function (Argument $argument) : bool {
                    return $argument->isDeprecated();
                },
            ),
            ResolvableField::create(
                'deprecationReason',
                Container::String(),
                static function (Argument $argument) : ?string {
                    return $argument->getDeprecationReason();
                },
            ),
        ]);
    }
}
