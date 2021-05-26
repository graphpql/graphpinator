<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

final class Field extends \Graphpinator\Type\Type
{
    protected const NAME = '__Field';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private \Graphpinator\Container\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Field\Field;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Container\Container::String()->notNull(),
                static function (\Graphpinator\Field\Field $field) : string {
                    return $field->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Field\Field $field) : ?string {
                    return $field->getDescription();
                },
            ),
            (new \Graphpinator\Field\ResolvableField(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (\Graphpinator\Field\Field $field, bool $includeDeprecated) : \Graphpinator\Argument\ArgumentSet {
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

                    return new \Graphpinator\Argument\ArgumentSet($filtered);
                },
            ))->setArguments(new \Graphpinator\Argument\ArgumentSet([
                \Graphpinator\Argument\Argument::create('includeDeprecated', \Graphpinator\Container\Container::Boolean()->notNull()
                )->setDefaultValue(false),
            ])),
            new \Graphpinator\Field\ResolvableField(
                'type',
                $this->container->getType('__Type')->notNull(),
                static function (\Graphpinator\Field\Field $field) : \Graphpinator\Type\Contract\Definition {
                    return $field->getType();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'isDeprecated',
                \Graphpinator\Container\Container::Boolean()->notNull(),
                static function (\Graphpinator\Field\Field $field) : bool {
                    return $field->isDeprecated();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'deprecationReason',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Field\Field $field) : ?string {
                    return $field->getDeprecationReason();
                },
            ),
        ]);
    }
}
