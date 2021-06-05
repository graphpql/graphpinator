<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

final class TypeSystemDirectiveLocation
{
    use \Nette\StaticClass;

    public const SCHEMA = 'SCHEMA';
    public const SCALAR = 'SCALAR';
    public const UNION = 'UNION';
    public const ENUM = 'ENUM';
    public const OBJECT = 'OBJECT';
    public const INTERFACE = 'INTERFACE';
    public const INPUT_OBJECT = 'INPUT_OBJECT';
    public const FIELD_DEFINITION = 'FIELD_DEFINITION';
    public const ARGUMENT_DEFINITION = 'ARGUMENT_DEFINITION';
    public const INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';
    public const ENUM_VALUE = 'ENUM_VALUE';
}
