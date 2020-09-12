<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class UnprintableValue extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'This value cannot be printed.';
}
