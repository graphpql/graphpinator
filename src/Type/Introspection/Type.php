<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

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
        return $rawValue instanceof \Graphpinator\Type\Contract\Definition;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'kind',
                $this->container->introspectionTypeKind()->notNull(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : string {
                    return $definition->getTypeKind();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?string {
                    return $definition instanceof \Graphpinator\Type\Contract\NamedDefinition
                        ? $definition->getName()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?string {
                    return $definition instanceof \Graphpinator\Type\Contract\NamedDefinition
                        ? $definition->getDescription()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'fields',
                $this->container->introspectionField()->notNull()->list(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Field\FieldSet {
                    return $definition instanceof \Graphpinator\Type\Contract\InterfaceImplementor
                        ? $definition->getFields()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'interfaces',
                $this->notNull()->list(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Utils\InterfaceSet {
                    return $definition instanceof \Graphpinator\Type\Contract\InterfaceImplementor
                        ? $definition->getInterfaces()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'possibleTypes',
                $this->notNull()->list(),
                function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Utils\ConcreteSet {
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
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Type\Scalar\EnumItemSet {
                    return $definition instanceof \Graphpinator\Type\Scalar\EnumType
                        ? $definition->getItems()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'inputFields',
                $this->container->introspectionInputValue()->notNull()->list(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Argument\ArgumentSet {
                    return $definition instanceof \Graphpinator\Type\InputType
                        ? $definition->getArguments()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'ofType',
                $this,
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Type\Contract\Definition {
                    return $definition instanceof \Graphpinator\Type\Contract\ModifierDefinition
                        ? $definition->getInnerType()
                        : null;
                },
            ),
        ]);
    }
}
