<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Contract\InterfaceImplementor;
use Graphpinator\Typesystem\Contract\ModifierType;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Contract\Type as TypeDef;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type as TypesystemType;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;

#[Description('Built-in introspection type')]
final class Type extends TypesystemType
{
    protected const NAME = '__Type';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof TypeDef;
    }

    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            ResolvableField::create(
                'kind',
                $this->container->getType('__TypeKind')->notNull(),
                static function (TypeDef $definition) : string {
                    return $definition->accept(new TypeKindVisitor());
                },
            ),
            ResolvableField::create(
                'name',
                Container::String(),
                static function (TypeDef $definition) : ?string {
                    return $definition instanceof NamedType
                        ? $definition->getName()
                        : null;
                },
            ),
            ResolvableField::create(
                'description',
                Container::String(),
                static function (TypeDef $definition) : ?string {
                    return $definition instanceof NamedType
                        ? $definition->getDescription()
                        : null;
                },
            ),
            ResolvableField::create(
                'fields',
                $this->container->getType('__Field')->notNull()->list(),
                static function (TypeDef $definition, bool $includeDeprecated) : ?FieldSet {
                    if (!$definition instanceof InterfaceImplementor) {
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

                    return new FieldSet($filtered);
                },
            )->setArguments(new ArgumentSet([
                Argument::create('includeDeprecated', Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            ResolvableField::create(
                'interfaces',
                $this->notNull()->list(),
                static function (TypeDef $definition) : ?InterfaceSet {
                    return $definition instanceof InterfaceImplementor
                        ? self::recursiveGetInterfaces($definition->getInterfaces())
                        : null;
                },
            ),
            ResolvableField::create(
                'possibleTypes',
                $this->notNull()->list(),
                function (TypeDef $definition) : ?TypeSet {
                    if ($definition instanceof UnionType) {
                        return $definition->getTypes();
                    }

                    if ($definition instanceof InterfaceType) {
                        $subTypes = [];

                        foreach ($this->container->getTypes() as $type) {
                            if ($type instanceof TypesystemType &&
                                $type->isInstanceOf($definition)) {
                                $subTypes[] = $type;
                            }
                        }

                        return new TypeSet($subTypes);
                    }

                    return null;
                },
            ),
            ResolvableField::create(
                'enumValues',
                $this->container->getType('__EnumValue')->notNull()->list(),
                static function (TypeDef $definition, bool $includeDeprecated) : ?EnumItemSet {
                    if (!$definition instanceof EnumType) {
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

                    return new EnumItemSet($filtered);
                },
            )->setArguments(new ArgumentSet([
                Argument::create('includeDeprecated', Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            ResolvableField::create(
                'inputFields',
                $this->container->getType('__InputValue')->notNull()->list(),
                static function (TypeDef $definition, bool $includeDeprecated) : ?ArgumentSet {
                    if (!$definition instanceof InputType) {
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

                    return new ArgumentSet($filtered);
                },
            )->setArguments(new ArgumentSet([
                Argument::create('includeDeprecated', Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            ResolvableField::create(
                'ofType',
                $this,
                static function (TypeDef $definition) : ?TypeDef {
                    return $definition instanceof ModifierType
                        ? $definition->getInnerType()
                        : null;
                },
            ),
            ResolvableField::create(
                'specifiedByURL',
                Container::String(),
                static function (TypeDef $definition) : ?string {
                    return $definition instanceof ScalarType
                        ? $definition->getSpecifiedByUrl()
                        : null;
                },
            ),
            ResolvableField::create(
                'isOneOf',
                Container::Boolean(),
                static function (TypeDef $definition) : ?bool {
                    return $definition instanceof InputType
                        ? $definition->isOneOf()
                        : null;
                },
            ),
        ]);
    }

    private static function recursiveGetInterfaces(InterfaceSet $implements) : InterfaceSet
    {
        $return = new InterfaceSet([]);

        foreach ($implements as $interface) {
            $return->merge(self::recursiveGetInterfaces($interface->getInterfaces()));
            $return[] = $interface;
        }

        return $return;
    }
}
