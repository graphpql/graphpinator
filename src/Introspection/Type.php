<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Argument\Argument;
use \Graphpinator\Typesystem\Argument\ArgumentSet;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Contract\Type as TypeDef;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\InterfaceSet;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class Type extends \Graphpinator\Typesystem\Type
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

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
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
                    return $definition instanceof \Graphpinator\Typesystem\Contract\NamedType
                        ? $definition->getName()
                        : null;
                },
            ),
            ResolvableField::create(
                'description',
                Container::String(),
                static function (TypeDef $definition) : ?string {
                    return $definition instanceof \Graphpinator\Typesystem\Contract\NamedType
                        ? $definition->getDescription()
                        : null;
                },
            ),
            ResolvableField::create(
                'fields',
                $this->container->getType('__Field')->notNull()->list(),
                static function (TypeDef $definition, bool $includeDeprecated) : ?\Graphpinator\Typesystem\Field\FieldSet {
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
            )->setArguments(new ArgumentSet([
                Argument::create('includeDeprecated', Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            ResolvableField::create(
                'interfaces',
                $this->notNull()->list(),
                static function (TypeDef $definition) : ?InterfaceSet {
                    return $definition instanceof \Graphpinator\Typesystem\Contract\InterfaceImplementor
                        ? self::recursiveGetInterfaces($definition->getInterfaces())
                        : null;
                },
            ),
            ResolvableField::create(
                'possibleTypes',
                $this->notNull()->list(),
                function (TypeDef $definition) : ?\Graphpinator\Typesystem\TypeSet {
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
            ResolvableField::create(
                'enumValues',
                $this->container->getType('__EnumValue')->notNull()->list(),
                static function (TypeDef $definition, bool $includeDeprecated) : ?\Graphpinator\Typesystem\EnumItem\EnumItemSet {
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
            )->setArguments(new ArgumentSet([
                Argument::create('includeDeprecated', Container::Boolean()->notNull())
                    ->setDefaultValue(false),
            ])),
            ResolvableField::create(
                'inputFields',
                $this->container->getType('__InputValue')->notNull()->list(),
                static function (TypeDef $definition, bool $includeDeprecated) : ?ArgumentSet {
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
                    return $definition instanceof \Graphpinator\Typesystem\Contract\ModifierType
                        ? $definition->getInnerType()
                        : null;
                },
            ),
            ResolvableField::create(
                'specifiedByURL',
                Container::String(),
                static function (TypeDef $definition) : ?string {
                    return $definition instanceof \Graphpinator\Typesystem\ScalarType
                        ? $definition->getSpecifiedByUrl()
                        : null;
                },
            ),
            ResolvableField::create(
                'isOneOf',
                Container::Boolean(),
                static function (TypeDef $definition) : ?bool {
                    return $definition instanceof \Graphpinator\Typesystem\InputType
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
