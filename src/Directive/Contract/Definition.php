<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface Definition extends \Graphpinator\Typesystem\Entity
{
    public const INTERFACE_TO_LOCATION = [
        \Graphpinator\Directive\Contract\ObjectLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::OBJECT,
            \Graphpinator\Type\Introspection\DirectiveLocation::INTERFACE,
        ],
        \Graphpinator\Directive\Contract\InputObjectLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::INPUT_OBJECT,
        ],
        \Graphpinator\Directive\Contract\ArgumentDefinitionLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::ARGUMENT_DEFINITION,
            \Graphpinator\Type\Introspection\DirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        \Graphpinator\Directive\Contract\FieldDefinitionLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::FIELD_DEFINITION,
        ],
        \Graphpinator\Directive\Contract\EnumItemLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::ENUM_VALUE,
        ],
        \Graphpinator\Directive\Contract\QueryLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::QUERY,
        ],
        \Graphpinator\Directive\Contract\MutationLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::MUTATION,
        ],
        \Graphpinator\Directive\Contract\SubscriptionLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::SUBSCRIPTION,
        ],
        \Graphpinator\Directive\Contract\FieldLocation::class => [
            \Graphpinator\Type\Introspection\DirectiveLocation::FIELD,
            \Graphpinator\Type\Introspection\DirectiveLocation::INLINE_FRAGMENT,
            \Graphpinator\Type\Introspection\DirectiveLocation::FRAGMENT_SPREAD,
        ],
    ];

    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    public function getLocations() : array;

    public function getArguments() : \Graphpinator\Argument\ArgumentSet;
}
