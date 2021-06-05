<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

final class ExecutableDirectiveLocation
{
    use \Nette\StaticClass;

    public const QUERY = 'QUERY';
    public const MUTATION = 'MUTATION';
    public const SUBSCRIPTION = 'SUBSCRIPTION';
    public const FIELD = 'FIELD';
    public const INLINE_FRAGMENT = 'INLINE_FRAGMENT';
    public const FRAGMENT_SPREAD = 'FRAGMENT_SPREAD';
    public const FRAGMENT_DEFINITION = 'FRAGMENT_DEFINITION';
    public const VARIABLE_DEFINITION = 'VARIABLE_DEFINITION';
}
