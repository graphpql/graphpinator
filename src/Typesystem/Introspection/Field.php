<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Introspection;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Contract\Type as TypeContract;
use Graphpinator\Typesystem\Field\Field as FieldDef;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;

#[Description('Built-in introspection type')]
final class Field extends Type
{
    protected const NAME = '__Field';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    #[\Override]
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof FieldDef;
    }

    #[\Override]
    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            ResolvableField::create(
                'name',
                Container::String()->notNull(),
                static function (FieldDef $field) : string {
                    return $field->getName();
                },
            ),
            ResolvableField::create(
                'description',
                Container::String(),
                static function (FieldDef $field) : ?string {
                    return $field->getDescription();
                },
            ),
            ResolvableField::create(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (FieldDef $field, bool $includeDeprecated) : ArgumentSet {
                    if ($includeDeprecated === true) {
                        return $field->getArguments();
                    }

                    $filtered = [];

                    foreach ($field->getArguments() as $argument) {
                        if ($argument->isDeprecated()) {
                            continue;
                        }

                        $filtered[] = $argument;
                    }

                    return new ArgumentSet($filtered);
                },
            )->setArguments(new ArgumentSet([
                Argument::create('includeDeprecated', Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            ResolvableField::create(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (FieldDef $field) : TypeContract {
                    return $field->getType();
                },
            ),
            ResolvableField::create(
                'isDeprecated',
                Container::Boolean()->notNull(),
                static function (FieldDef $field) : bool {
                    return $field->isDeprecated();
                },
            ),
            ResolvableField::create(
                'deprecationReason',
                Container::String(),
                static function (FieldDef $field) : ?string {
                    return $field->getDeprecationReason();
                },
            ),
        ]);
    }
}
