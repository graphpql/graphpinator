<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class InvalidDirectiveResult extends ResolverError
{
    public const MESSAGE = 'Directive callback must return DirectiveResult string.';
}
