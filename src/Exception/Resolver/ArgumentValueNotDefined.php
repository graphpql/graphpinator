<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class ArgumentValueNotDefined extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'ArgumentValue is not defined.';
}
