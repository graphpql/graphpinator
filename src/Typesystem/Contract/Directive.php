<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface Directive extends \Graphpinator\Typesystem\Contract\Entity
{
    public const INTERFACE_TO_LOCATION = [
        // Typesystem
        \Graphpinator\Typesystem\Location\SchemaLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::SCHEMA,
        ],
        \Graphpinator\Typesystem\Location\ObjectLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::OBJECT,
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::INTERFACE,
        ],
        \Graphpinator\Typesystem\Location\InputObjectLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::INPUT_OBJECT,
        ],
        \Graphpinator\Typesystem\Location\UnionLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::UNION,
        ],
        \Graphpinator\Typesystem\Location\EnumLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::ENUM,
        ],
        \Graphpinator\Typesystem\Location\ScalarLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::SCALAR,
        ],
        \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FieldDefinitionLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\EnumItemLocation::class => [
            \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::ENUM_VALUE,
        ],
        // Executable
        \Graphpinator\Typesystem\Location\QueryLocation::class => [
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::QUERY,
        ],
        \Graphpinator\Typesystem\Location\MutationLocation::class => [
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::MUTATION,
        ],
        \Graphpinator\Typesystem\Location\SubscriptionLocation::class => [
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::SUBSCRIPTION,
        ],
        \Graphpinator\Typesystem\Location\VariableDefinitionLocation::class => [
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::VARIABLE_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FragmentDefinitionLocation::class => [
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::FRAGMENT_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FieldLocation::class => [
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::FIELD,
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::INLINE_FRAGMENT,
            \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::FRAGMENT_SPREAD,
        ],
    ];

    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    public function getLocations() : array;

    public function getArguments() : \Graphpinator\Typesystem\Argument\ArgumentSet;
}
