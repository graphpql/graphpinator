<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class VariableValueNotDefined extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'VariableValue is not defined.';
}
