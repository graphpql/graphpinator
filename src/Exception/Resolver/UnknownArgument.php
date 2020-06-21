<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class UnknownArgument extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown argument provided.';
}
