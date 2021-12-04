<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class Field extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Field';

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Typesystem\Field\Field;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'name',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : string {
                    return $field->getName();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : ?string {
                    return $field->getDescription();
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (
                    \Graphpinator\Typesystem\Field\Field $field,
                    bool $includeDeprecated,
                ) : \Graphpinator\Typesystem\Argument\ArgumentSet {
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
                    \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                )->setDefaultValue(false),
            ])),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : \Graphpinator\Typesystem\Contract\Type {
                    return $field->getType();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'isDeprecated',
                \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : bool {
                    return $field->isDeprecated();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'deprecationReason',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Field\Field $field) : ?string {
                    return $field->getDeprecationReason();
                },
            ),
        ]);
    }
}
