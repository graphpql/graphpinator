<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class FieldResultAbstract extends ResolverError
{
    public const MESSAGE = 'Abstract type fields need to return ResolveResult with concrete resolution.';
}
