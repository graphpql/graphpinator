<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

use \Graphpinator\Type\Contract\Definition;

final class Type extends \Graphpinator\Type\Type
{
    protected const NAME = '__Type';
    protected const DESCRIPTION = 'Built-in introspection type.';

    private \Graphpinator\Type\Container\Container $container;

    public function __construct(\Graphpinator\Type\Container\Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function validateNonNullValue($rawValue) : bool
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
                \Graphpinator\Type\Container\Container::String(),
                static function (Definition $definition) : ?string {
                    return $definition instanceof \Graphpinator\Type\Contract\NamedDefinition
                        ? $definition->getName()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function (Definition $definition) : ?string {
                    return $definition instanceof \Graphpinator\Type\Contract\NamedDefinition
                        ? $definition->getDescription()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'fields',
                $this->container->introspectionField()->notNull()->list(),
                static function (Definition $definition, \Graphpinator\Resolver\ArgumentValueSet $arguments) : ?\Graphpinator\Field\FieldSet {
                    if (!$definition instanceof \Graphpinator\Type\Contract\InterfaceImplementor) {
                        return null;
                    }

                    if ($arguments['includeDeprecated']->getRawValue() === true) {
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
                new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument('includeDeprecated', \Graphpinator\Type\Container\Container::Boolean(), false),
                ]),
            ),
            new \Graphpinator\Field\ResolvableField(
                'interfaces',
                $this->notNull()->list(),
                static function (Definition $definition) : ?\Graphpinator\Utils\InterfaceSet {
                    return $definition instanceof \Graphpinator\Type\Contract\InterfaceImplementor
                        ? $definition->getInterfaces()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'possibleTypes',
                $this->notNull()->list(),
                function (Definition $definition) : ?\Graphpinator\Utils\ConcreteSet {
                    if ($definition instanceof \Graphpinator\Type\UnionType) {
                        return $definition->getTypes();
                    }

                    if ($definition instanceof \Graphpinator\Type\InterfaceType) {
                        $subTypes = [];

                        foreach ($this->container->getAllTypes() as $type) {
                            if ($type instanceof \Graphpinator\Type\Type &&
                                $type->isInstanceOf($definition)) {
                                $subTypes[] = $type;
                            }
                        }

                        return new \Graphpinator\Utils\ConcreteSet($subTypes);
                    }

                    return null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'enumValues',
                $this->container->introspectionEnumValue()->notNull()->list(),
                static function (Definition $definition, \Graphpinator\Resolver\ArgumentValueSet $arguments) : ?\Graphpinator\Type\Scalar\EnumItemSet {
                    if (!$definition instanceof \Graphpinator\Type\Scalar\EnumType) {
                        return null;
                    }

                    if ($arguments['includeDeprecated']->getRawValue() === true) {
                        return $definition->getItems();
                    }

                    $filtered = [];

                    foreach ($definition->getItems() as $enumItem) {
                        if ($enumItem->isDeprecated()) {
                            continue;
                        }

                        $filtered[] = $enumItem;
                    }

                    return new \Graphpinator\Type\Scalar\EnumItemSet($filtered);
                },
                new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument('includeDeprecated', \Graphpinator\Type\Container\Container::Boolean(), false),
                ]),
            ),
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
