<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

use \Graphpinator\Type\Contract\Definition;

final class Type extends \Graphpinator\Type\Type
{
    protected const NAME = '__Type';
    protected const DESCRIPTION = 'Built-in introspection type.';

    private \Graphpinator\Container\Container $container;

    public function __construct(\Graphpinator\Container\Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof Definition;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'kind',
                $this->container->introspectionTypeKind()->notNull(),
                static function (Definition $definition) : string {
                    return $definition->getTypeKind();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Container\Container::String(),
                static function (Definition $definition) : ?string {
                    return $definition instanceof \Graphpinator\Type\Contract\NamedDefinition
                        ? $definition->getName()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Container\Container::String(),
                static function (Definition $definition) : ?string {
                    return $definition instanceof \Graphpinator\Type\Contract\NamedDefinition
                        ? $definition->getDescription()
                        : null;
                },
            ),
            \Graphpinator\Field\ResolvableField::create(
                'fields',
                $this->container->introspectionField()->notNull()->list(),
                static function (Definition $definition, bool $includeDeprecated) : ?\Graphpinator\Field\FieldSet {
                    if (!$definition instanceof \Graphpinator\Type\Contract\InterfaceImplementor) {
                        return null;
                    }

                    if ($includeDeprecated === true) {
                        return $definition->getFields();
                    }

                    $filtered = [];

                    foreach ($definition->getFields() as $field) {
                        if ($field->isDeprecated()) {
                            continue;
                        }

                        $filtered[] = $field;
                    }

                    return new \Graphpinator\Field\FieldSet($filtered);
                },
            )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                \Graphpinator\Argument\Argument::create('includeDeprecated', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            new \Graphpinator\Field\ResolvableField(
                'interfaces',
                $this->notNull()->list(),
                static function (Definition $definition) : ?\Graphpinator\Type\InterfaceSet {
                    return $definition instanceof \Graphpinator\Type\Contract\InterfaceImplementor
                        ? $definition->getInterfaces()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'possibleTypes',
                $this->notNull()->list(),
                function (Definition $definition) : ?\Graphpinator\Type\ConcreteSet {
                    if ($definition instanceof \Graphpinator\Type\UnionType) {
                        return $definition->getTypes();
                    }

                    if ($definition instanceof \Graphpinator\Type\InterfaceType) {
                        $subTypes = [];

                        foreach ($this->container->getTypes() as $type) {
                            if ($type instanceof \Graphpinator\Type\Type &&
                                $type->isInstanceOf($definition)) {
                                $subTypes[] = $type;
                            }
                        }

                        return new \Graphpinator\Type\ConcreteSet($subTypes);
                    }

                    return null;
                },
            ),
            \Graphpinator\Field\ResolvableField::create(
                'enumValues',
                $this->container->introspectionEnumValue()->notNull()->list(),
                static function (Definition $definition, bool $includeDeprecated) : ?\Graphpinator\Type\Enum\EnumItemSet {
                    if (!$definition instanceof \Graphpinator\Type\EnumType) {
                        return null;
                    }

                    if ($includeDeprecated === true) {
                        return $definition->getItems();
                    }

                    $filtered = [];

                    foreach ($definition->getItems() as $enumItem) {
                        if ($enumItem->isDeprecated()) {
                            continue;
                        }

                        $filtered[] = $enumItem;
                    }

                    return new \Graphpinator\Type\Enum\EnumItemSet($filtered);
                },
            )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                \Graphpinator\Argument\Argument::create('includeDeprecated', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            new \Graphpinator\Field\ResolvableField(
                'inputFields',
                $this->container->introspectionInputValue()->notNull()->list(),
                static function (Definition $definition) : ?\Graphpinator\Argument\ArgumentSet {
                    return $definition instanceof \Graphpinator\Type\InputType
                        ? $definition->getArguments()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'ofType',
                $this,
                static function (Definition $definition) : ?Definition {
                    return $definition instanceof \Graphpinator\Type\Contract\ModifierDefinition
                        ? $definition->getInnerType()
                        : null;
                },
            ),
        ]);
    }
}
