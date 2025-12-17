<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Introspection;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Contract\Type as TypeContract;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\ArgumentValue;

#[Description('Built-in introspection type')]
final class InputValue extends Type
{
    protected const NAME = '__InputValue';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    #[\Override]
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof Argument;
    }

    #[\Override]
    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
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
                static function (Argument $argument) : TypeContract {
                    return $argument->getType();
                },
            ),
            ResolvableField::create(
                'defaultValue',
                Container::String(),
                static function (Argument $argument) : ?string {
                    return $argument->getDefaultValue() instanceof ArgumentValue
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
