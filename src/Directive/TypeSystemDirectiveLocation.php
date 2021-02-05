<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class TypeSystemDirectiveLocation
{
    use \Nette\StaticClass;

    public const SCHEMA = 'SCHEMA'; // currently not supported
    public const SCALAR = 'SCALAR'; // currently not supported
    public const UNION = 'UNION'; // currently not supported
    public const ENUM = 'ENUM'; // currently not supported
    public const OBJECT = 'OBJECT';
    public const INTERFACE = 'INTERFACE';
    public const INPUT_OBJECT = 'INPUT_OBJECT';
    public const FIELD_DEFINITION = 'FIELD_DEFINITION';
    public const ARGUMENT_DEFINITION = 'ARGUMENT_DEFINITION';
    public const INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';
    public const ENUM_VALUE = 'ENUM_VALUE';
}
