<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

enum TypeSystemDirectiveLocation : string
{
    case SCHEMA = 'SCHEMA';
    case SCALAR = 'SCALAR';
    case UNION = 'UNION';
    case ENUM = 'ENUM';
    case OBJECT = 'OBJECT';
    case INTERFACE = 'INTERFACE';
    case INPUT_OBJECT = 'INPUT_OBJECT';
    case FIELD_DEFINITION = 'FIELD_DEFINITION';
    case ARGUMENT_DEFINITION = 'ARGUMENT_DEFINITION';
    case INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';
    case ENUM_VALUE = 'ENUM_VALUE';
}
