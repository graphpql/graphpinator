<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class DirectiveLocation extends \Graphpinator\Type\Scalar\EnumType
{
    protected const NAME = '__DirectiveLocation';
    protected const DESCRIPTION = 'Built-in introspection enum.';

    public const QUERY = 'QUERY';
    public const MUTATION = 'MUTATION';
    public const SUBSCRIPTION = 'SUBSCRIPTION';
    public const FIELD = 'FIELD';
    public const FRAGMENT_SPREAD = 'FRAGMENT_SPREAD';
    public const INLINE_FRAGMENT = 'INLINE_FRAGMENT';
    public const SCHEMA = 'SCHEMA';
    public const SCALAR = 'SCALAR';
    public const OBJECT = 'OBJECT';
    public const FIELD_DEFINITION = 'FIELD_DEFINITION';
    public const ARGUMENT_DEFINITION = 'ARGUMENT_DEFINITION';
    public const INTERFACE = 'INTERFACE';
    public const UNION = 'UNION';
    public const ENUM = 'ENUM';
    public const ENUM_VALUE = 'ENUM_VALUE';
    public const INPUT_OBJECT = 'INPUT_OBJECT';
    public const INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';

    public function __construct()
    {
        parent::__construct(self::fromConstants());
    }
}
