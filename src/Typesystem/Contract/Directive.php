<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;

interface Directive extends \Graphpinator\Typesystem\Contract\Entity
{
    public const INTERFACE_TO_LOCATION = [
        // Typesystem
        \Graphpinator\Typesystem\Location\SchemaLocation::class => [
            TypeSystemDirectiveLocation::SCHEMA,
        ],
        \Graphpinator\Typesystem\Location\ObjectLocation::class => [
            TypeSystemDirectiveLocation::OBJECT,
            TypeSystemDirectiveLocation::INTERFACE,
        ],
        \Graphpinator\Typesystem\Location\InputObjectLocation::class => [
            TypeSystemDirectiveLocation::INPUT_OBJECT,
        ],
        \Graphpinator\Typesystem\Location\UnionLocation::class => [
            TypeSystemDirectiveLocation::UNION,
        ],
        \Graphpinator\Typesystem\Location\EnumLocation::class => [
            TypeSystemDirectiveLocation::ENUM,
        ],
        \Graphpinator\Typesystem\Location\ScalarLocation::class => [
            TypeSystemDirectiveLocation::SCALAR,
        ],
        \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation::class => [
            TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
            TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FieldDefinitionLocation::class => [
            TypeSystemDirectiveLocation::FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\EnumItemLocation::class => [
            TypeSystemDirectiveLocation::ENUM_VALUE,
        ],
        // Executable
        \Graphpinator\Typesystem\Location\QueryLocation::class => [
            ExecutableDirectiveLocation::QUERY,
        ],
        \Graphpinator\Typesystem\Location\MutationLocation::class => [
            ExecutableDirectiveLocation::MUTATION,
        ],
        \Graphpinator\Typesystem\Location\SubscriptionLocation::class => [
            ExecutableDirectiveLocation::SUBSCRIPTION,
        ],
        \Graphpinator\Typesystem\Location\VariableDefinitionLocation::class => [
            ExecutableDirectiveLocation::VARIABLE_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FragmentDefinitionLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FieldLocation::class => [
            ExecutableDirectiveLocation::FIELD,
        ],
        \Graphpinator\Typesystem\Location\InlineFragmentLocation::class => [
            ExecutableDirectiveLocation::INLINE_FRAGMENT,
        ],
        \Graphpinator\Typesystem\Location\FragmentSpreadLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_SPREAD,
        ],
    ];

    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    public function getLocations() : array;

    public function getArguments() : \Graphpinator\Typesystem\Argument\ArgumentSet;
}
