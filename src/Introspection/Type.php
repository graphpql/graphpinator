<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

final class Type extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Type';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Typesystem\Contract\Type;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'kind',
                $this->container->getType('__TypeKind')->notNull(),
                static function (\Graphpinator\Typesystem\Contract\Type $definition) : string {
                    return $definition->accept(new TypeKindVisitor());
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'name',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Contract\Type $definition) : ?string {
                    return $definition instanceof \Graphpinator\Typesystem\Contract\NamedType
                        ? $definition->getName()
                        : null;
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Contract\Type $definition) : ?string {
                    return $definition instanceof \Graphpinator\Typesystem\Contract\NamedType
                        ? $definition->getDescription()
                        : null;
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'fields',
                $this->container->getType('__Field')->notNull()->list(),
                static function (
                    \Graphpinator\Typesystem\Contract\Type $definition,
                    bool $includeDeprecated,
                ) : ?\Graphpinator\Typesystem\Field\FieldSet {
                    if (!$definition instanceof \Graphpinator\Typesystem\Contract\InterfaceImplementor) {
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

                    return new \Graphpinator\Typesystem\Field\FieldSet($filtered);
                },
            )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                \Graphpinator\Typesystem\Argument\Argument::create(
                    'includeDeprecated',
                    \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                )->setDefaultValue(false),
            ])),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'interfaces',
                $this->notNull()->list(),
                static function (\Graphpinator\Typesystem\Contract\Type $definition) : ?\Graphpinator\Typesystem\InterfaceSet {
                    return $definition instanceof \Graphpinator\Typesystem\Contract\InterfaceImplementor
                        ? self::recursiveGetInterfaces($definition->getInterfaces())
                        : null;
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'possibleTypes',
                $this->notNull()->list(),
                function (\Graphpinator\Typesystem\Contract\Type $definition) : ?\Graphpinator\Typesystem\TypeSet {
                    if ($definition instanceof \Graphpinator\Typesystem\UnionType) {
                        return $definition->getTypes();
                    }

                    if ($definition instanceof \Graphpinator\Typesystem\InterfaceType) {
                        $subTypes = [];

                        foreach ($this->container->getTypes() as $type) {
                            if ($type instanceof \Graphpinator\Typesystem\Type &&
                                $type->isInstanceOf($definition)) {
                                $subTypes[] = $type;
                            }
                        }

                        return new \Graphpinator\Typesystem\TypeSet($subTypes);
                    }

                    return null;
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'enumValues',
                $this->container->getType('__EnumValue')->notNull()->list(),
                static function (
                    \Graphpinator\Typesystem\Contract\Type $definition,
                    bool $includeDeprecated,
                ) : ?\Graphpinator\Typesystem\EnumItem\EnumItemSet {
                    if (!$definition instanceof \Graphpinator\Typesystem\EnumType) {
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

                    return new \Graphpinator\Typesystem\EnumItem\EnumItemSet($filtered);
                },
            )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                \Graphpinator\Typesystem\Argument\Argument::create(
                    'includeDeprecated',
                    \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                )->setDefaultValue(false),
            ])),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'inputFields',
                $this->container->getType('__InputValue')->notNull()->list(),
                static function (
                    \Graphpinator\Typesystem\Contract\Type $definition,
                    bool $includeDeprecated,
                ) : ?\Graphpinator\Typesystem\Argument\ArgumentSet {
                    if (!$definition instanceof \Graphpinator\Typesystem\InputType) {
                        return null;
                    }

                    if ($includeDeprecated === true) {
                        return $definition->getArguments();
                    }

                    $filtered = [];

                    foreach ($definition->getArguments() as $argument) {
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
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'ofType',
                $this,
                static function (\Graphpinator\Typesystem\Contract\Type $definition) : ?\Graphpinator\Typesystem\Contract\Type {
                    return $definition instanceof \Graphpinator\Typesystem\Contract\ModifierType
                        ? $definition->getInnerType()
                        : null;
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'specifiedByURL',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Contract\Type $definition) : ?string {
                    return $definition instanceof \Graphpinator\Typesystem\ScalarType
                        ? $definition->getSpecifiedByUrl()
                        : null;
                },
            ),
        ]);
    }

    private static function recursiveGetInterfaces(\Graphpinator\Typesystem\InterfaceSet $implements) : \Graphpinator\Typesystem\InterfaceSet
    {
        $return = new \Graphpinator\Typesystem\InterfaceSet([]);

        foreach ($implements as $interface) {
            $return->merge(self::recursiveGetInterfaces($interface->getInterfaces()));
            $return[] = $interface;
        }

        return $return;
    }
}
