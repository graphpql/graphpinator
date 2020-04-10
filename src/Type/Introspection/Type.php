<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Type extends \Graphpinator\Type\Type
{
    protected const NAME = '__Type';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct()
    {
        parent::__construct();
    }

    public function validateNonNullValue($rawValue): void
    {
        if (!$rawValue instanceof \Graphpinator\Type\Contract\Definition) {
            throw new \Exception('Invalid resolve value for type __Type');
        }
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'kind',
                \Graphpinator\Type\Container\Container::introspectionTypeKind()->notNull(),
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
                \Graphpinator\Type\Container\Container::introspectionField()->notNull()->list(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Field\FieldSet {
                    return $definition instanceof \Graphpinator\Type\Utils\InterfaceImplementor
                        ? $definition->getFields()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'interfaces',
                $this->notNull()->list(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Type\Utils\InterfaceSet {
                    return $definition instanceof \Graphpinator\Type\Utils\InterfaceImplementor
                        ? $definition->getInterfaces()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'possibleTypes',
                $this->notNull()->list(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Type\Utils\ConcreteSet {
                    if ($definition instanceof \Graphpinator\Type\UnionType) {
                        return $definition->getTypes();
                    }

                    if ($definition instanceof \Graphpinator\Type\InterfaceType) {
                        return null;
                    }

                    return null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'enumValues',
                \Graphpinator\Type\Container\Container::introspectionEnumValue()->notNull()->list(),
                static function (\Graphpinator\Type\Contract\Definition $definition) : ?\Graphpinator\Type\Scalar\EnumItemSet {
                    return $definition instanceof \Graphpinator\Type\Scalar\EnumType
                        ? $definition->getItems()
                        : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'inputFields',
                \Graphpinator\Type\Container\Container::introspectionInputValue()->notNull()->list(),
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
