<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface Definition extends \Graphpinator\Typesystem\Entity
{
    public const INTERFACE_TO_LOCATION = [
        \Graphpinator\Directive\Contract\ObjectLocation::class => [
            \Graphpinator\Directive\TypeSystemDirectiveLocation::OBJECT,
            \Graphpinator\Directive\TypeSystemDirectiveLocation::INTERFACE,
        ],
        \Graphpinator\Directive\Contract\InputObjectLocation::class => [
            \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_OBJECT,
        ],
        \Graphpinator\Directive\Contract\ArgumentDefinitionLocation::class => [
            \Graphpinator\Directive\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
            \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        \Graphpinator\Directive\Contract\FieldDefinitionLocation::class => [
            \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION,
        ],
        \Graphpinator\Directive\Contract\EnumItemLocation::class => [
            \Graphpinator\Directive\TypeSystemDirectiveLocation::ENUM_VALUE,
        ],
        \Graphpinator\Directive\Contract\QueryLocation::class => [
            \Graphpinator\Directive\ExecutableDirectiveLocation::QUERY,
        ],
        \Graphpinator\Directive\Contract\MutationLocation::class => [
            \Graphpinator\Directive\ExecutableDirectiveLocation::MUTATION,
        ],
        \Graphpinator\Directive\Contract\SubscriptionLocation::class => [
            \Graphpinator\Directive\ExecutableDirectiveLocation::SUBSCRIPTION,
        ],
        \Graphpinator\Directive\Contract\FieldLocation::class => [
            \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD,
            \Graphpinator\Directive\ExecutableDirectiveLocation::INLINE_FRAGMENT,
            \Graphpinator\Directive\ExecutableDirectiveLocation::FRAGMENT_SPREAD,
        ],
    ];

    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    public function getLocations() : array;

    public function getArguments() : \Graphpinator\Argument\ArgumentSet;
}
