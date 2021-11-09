<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Argument\ArgumentSet;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

final class Field extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Field';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Typesystem\Field\Field;
    }

    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            new ResolvableField(
                'name',
                Container::String()->notNull(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : string {
                    return $field->getName();
                },
            ),
            new ResolvableField(
                'description',
                Container::String(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : ?string {
                    return $field->getDescription();
                },
            ),
            ResolvableField::create(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (
                    \Graphpinator\Typesystem\Field\Field $field,
                    bool $includeDeprecated,
                ) : ArgumentSet {
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

                    return new \Graphpinator\Typesystem\Argument\ArgumentSet($filtered);
                },
            )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                \Graphpinator\Typesystem\Argument\Argument::create(
                    'includeDeprecated',
                    Container::Boolean()->notNull(),
                )->setDefaultValue(false),
            ])),
            new ResolvableField(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : \Graphpinator\Typesystem\Contract\Type {
                    return $field->getType();
                },
            ),
            new ResolvableField(
                'isDeprecated',
                Container::Boolean()->notNull(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : bool {
                    return $field->isDeprecated();
                },
            ),
            new ResolvableField(
                'deprecationReason',
                Container::String(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : ?string {
                    return $field->getDeprecationReason();
                },
            ),
        ]);
    }
}
