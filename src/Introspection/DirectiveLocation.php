<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type.')]
final class DirectiveLocation extends \Graphpinator\Typesystem\EnumType
{
    public const QUERY = ExecutableDirectiveLocation::QUERY;
    public const MUTATION = ExecutableDirectiveLocation::MUTATION;
    public const SUBSCRIPTION = ExecutableDirectiveLocation::SUBSCRIPTION;
    public const FIELD = ExecutableDirectiveLocation::FIELD;
    public const INLINE_FRAGMENT = ExecutableDirectiveLocation::INLINE_FRAGMENT;
    public const FRAGMENT_SPREAD = ExecutableDirectiveLocation::FRAGMENT_SPREAD;
    public const FRAGMENT_DEFINITION = ExecutableDirectiveLocation::FRAGMENT_DEFINITION;
    public const VARIABLE_DEFINITION = ExecutableDirectiveLocation::VARIABLE_DEFINITION;
    public const SCHEMA = TypeSystemDirectiveLocation::SCHEMA;
    public const SCALAR = TypeSystemDirectiveLocation::SCALAR;
    public const INPUT_OBJECT = TypeSystemDirectiveLocation::INPUT_OBJECT;
    public const OBJECT = TypeSystemDirectiveLocation::OBJECT;
    public const INTERFACE = TypeSystemDirectiveLocation::INTERFACE;
    public const UNION = TypeSystemDirectiveLocation::UNION;
    public const FIELD_DEFINITION = TypeSystemDirectiveLocation::FIELD_DEFINITION;
    public const ARGUMENT_DEFINITION = TypeSystemDirectiveLocation::ARGUMENT_DEFINITION;
    public const INPUT_FIELD_DEFINITION = TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION;
    public const ENUM = TypeSystemDirectiveLocation::ENUM;
    public const ENUM_VALUE = TypeSystemDirectiveLocation::ENUM_VALUE;

    protected const NAME = '__DirectiveLocation';

    public function __construct()
    {
        parent::__construct(self::fromConstants());
    }
}
