<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

enum ExecutableDirectiveLocation : string
{
    case QUERY = 'QUERY';
    case MUTATION = 'MUTATION';
    case SUBSCRIPTION = 'SUBSCRIPTION';
    case FIELD = 'FIELD';
    case INLINE_FRAGMENT = 'INLINE_FRAGMENT';
    case FRAGMENT_SPREAD = 'FRAGMENT_SPREAD';
    case FRAGMENT_DEFINITION = 'FRAGMENT_DEFINITION';
    case VARIABLE_DEFINITION = 'VARIABLE_DEFINITION';
}
