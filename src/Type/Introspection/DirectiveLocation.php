<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class DirectiveLocation extends \Graphpinator\Type\EnumType
{
    public const QUERY = \Graphpinator\Directive\ExecutableDirectiveLocation::QUERY;
    public const MUTATION = \Graphpinator\Directive\ExecutableDirectiveLocation::MUTATION;
    public const SUBSCRIPTION = \Graphpinator\Directive\ExecutableDirectiveLocation::SUBSCRIPTION;
    public const FIELD = \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD;
    public const INLINE_FRAGMENT = \Graphpinator\Directive\ExecutableDirectiveLocation::INLINE_FRAGMENT;
    public const FRAGMENT_SPREAD = \Graphpinator\Directive\ExecutableDirectiveLocation::FRAGMENT_SPREAD;
    public const FRAGMENT_DEFINITION = \Graphpinator\Directive\ExecutableDirectiveLocation::FRAGMENT_DEFINITION;
    public const VARIABLE_DEFINITION = \Graphpinator\Directive\ExecutableDirectiveLocation::VARIABLE_DEFINITION;
    public const SCHEMA = \Graphpinator\Directive\TypeSystemDirectiveLocation::SCHEMA;
    public const SCALAR = \Graphpinator\Directive\TypeSystemDirectiveLocation::SCALAR;
    public const INPUT_OBJECT = \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_OBJECT;
    public const OBJECT = \Graphpinator\Directive\TypeSystemDirectiveLocation::OBJECT;
    public const INTERFACE = \Graphpinator\Directive\TypeSystemDirectiveLocation::INTERFACE;
    public const UNION = \Graphpinator\Directive\TypeSystemDirectiveLocation::UNION;
    public const FIELD_DEFINITION = \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION;
    public const ARGUMENT_DEFINITION = \Graphpinator\Directive\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION;
    public const INPUT_FIELD_DEFINITION = \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION;
    public const ENUM = \Graphpinator\Directive\TypeSystemDirectiveLocation::ENUM;
    public const ENUM_VALUE = \Graphpinator\Directive\TypeSystemDirectiveLocation::ENUM_VALUE;

    protected const NAME = '__DirectiveLocation';
    protected const DESCRIPTION = 'Built-in introspection enum.';

    public function __construct()
    {
        parent::__construct(self::fromConstants());
    }
}
