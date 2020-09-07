<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class UnknownFieldForInputValue extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown field for input value.';
}
