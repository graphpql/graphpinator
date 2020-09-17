<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class MissingVariable extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Variable is missing.';
}
