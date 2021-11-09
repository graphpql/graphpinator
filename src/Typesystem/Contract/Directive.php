<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use \Graphpinator\Typesystem\Location\EnumItemLocation;
use \Graphpinator\Typesystem\Location\EnumLocation;
use \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use \Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use \Graphpinator\Typesystem\Location\FieldLocation;
use \Graphpinator\Typesystem\Location\FragmentDefinitionLocation;
use \Graphpinator\Typesystem\Location\FragmentSpreadLocation;
use \Graphpinator\Typesystem\Location\InlineFragmentLocation;
use \Graphpinator\Typesystem\Location\InputObjectLocation;
use \Graphpinator\Typesystem\Location\MutationLocation;
use \Graphpinator\Typesystem\Location\ObjectLocation;
use \Graphpinator\Typesystem\Location\QueryLocation;
use \Graphpinator\Typesystem\Location\ScalarLocation;
use \Graphpinator\Typesystem\Location\SchemaLocation;
use \Graphpinator\Typesystem\Location\SubscriptionLocation;
use \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;
use \Graphpinator\Typesystem\Location\UnionLocation;
use \Graphpinator\Typesystem\Location\VariableDefinitionLocation;

interface Directive extends \Graphpinator\Typesystem\Contract\Entity
{
    public const INTERFACE_TO_LOCATION = [
        // Typesystem
        SchemaLocation::class => [
            TypeSystemDirectiveLocation::SCHEMA,
        ],
        ObjectLocation::class => [
            TypeSystemDirectiveLocation::OBJECT,
            TypeSystemDirectiveLocation::INTERFACE,
        ],
        InputObjectLocation::class => [
            TypeSystemDirectiveLocation::INPUT_OBJECT,
        ],
        UnionLocation::class => [
            TypeSystemDirectiveLocation::UNION,
        ],
        EnumLocation::class => [
            TypeSystemDirectiveLocation::ENUM,
        ],
        ScalarLocation::class => [
            TypeSystemDirectiveLocation::SCALAR,
        ],
        ArgumentDefinitionLocation::class => [
            TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
            TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        FieldDefinitionLocation::class => [
            TypeSystemDirectiveLocation::FIELD_DEFINITION,
        ],
        EnumItemLocation::class => [
            TypeSystemDirectiveLocation::ENUM_VALUE,
        ],
        // Executable
        QueryLocation::class => [
            ExecutableDirectiveLocation::QUERY,
        ],
        MutationLocation::class => [
            ExecutableDirectiveLocation::MUTATION,
        ],
        SubscriptionLocation::class => [
            ExecutableDirectiveLocation::SUBSCRIPTION,
        ],
        VariableDefinitionLocation::class => [
            ExecutableDirectiveLocation::VARIABLE_DEFINITION,
        ],
        FragmentDefinitionLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_DEFINITION,
        ],
        FieldLocation::class => [
            ExecutableDirectiveLocation::FIELD,
        ],
        InlineFragmentLocation::class => [
            ExecutableDirectiveLocation::INLINE_FRAGMENT,
        ],
        FragmentSpreadLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_SPREAD,
        ],
    ];

    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    public function getLocations() : array;

    public function getArguments() : \Graphpinator\Typesystem\Argument\ArgumentSet;
}
