<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Type\Contract\Definition;

final class Type extends \Graphpinator\Type\Type
{
    protected const NAME = '__Type';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private \Graphpinator\Container\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof Definition;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            \Graphpinator\Field\ResolvableField::create(
                'kind',
                $this->container->getType('__TypeKind')->notNull(),
                static function (Definition $definition) : string {
                    return $definition->accept(new TypeKindVisitor());
                },
            ),
            \Graphpinator\Field\ResolvableField::create(
                'name',
                \Graphpinator\Container\Container::String(),
                static function (Definition $definition) : ?string {
                    return $definition instanceof \Graphpinator\Type\Contract\NamedDefinition
                        ? $definition->getName()
                        : null;
                },
            ),
            \Graphpinator\Field\ResolvableField::create(
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
                $this->container->getType('__Field')->notNull()->list(),
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
            \Graphpinator\Field\ResolvableField::create(
                'interfaces',
                $this->notNull()->list(),
                static function (Definition $definition) : ?\Graphpinator\Type\InterfaceSet {
                    return $definition instanceof \Graphpinator\Type\Contract\InterfaceImplementor
                        ? self::recursiveGetInterfaces($definition->getInterfaces())
                        : null;
                },
            ),
            \Graphpinator\Field\ResolvableField::create(
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
                $this->container->getType('__EnumValue')->notNull()->list(),
                static function (Definition $definition, bool $includeDeprecated) : ?\Graphpinator\EnumItem\EnumItemSet {
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

                    return new \Graphpinator\EnumItem\EnumItemSet($filtered);
                },
            )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                \Graphpinator\Argument\Argument::create('includeDeprecated', \Graphpinator\Container\Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            \Graphpinator\Field\ResolvableField::create(
                'inputFields',
                $this->container->getType('__InputValue')->notNull()->list(),
                static function (Definition $definition) : ?\Graphpinator\Argument\ArgumentSet {
                    return $definition instanceof \Graphpinator\Type\InputType
                        ? $definition->getArguments()
                        : null;
                },
            ),
            \Graphpinator\Field\ResolvableField::create(
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

    private static function recursiveGetInterfaces(\Graphpinator\Type\InterfaceSet $implements) : \Graphpinator\Type\InterfaceSet
    {
        $return = new \Graphpinator\Type\InterfaceSet([]);

        foreach ($implements as $interface) {
            $return->merge(self::recursiveGetInterfaces($interface->getInterfaces()));
            $return[] = $interface;
        }

        return $return;
    }
}
