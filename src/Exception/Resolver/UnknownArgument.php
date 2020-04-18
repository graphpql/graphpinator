<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class UnknownArgument extends ResolverError
{
    public const MESSAGE = 'Unknown argument provided.';
}
