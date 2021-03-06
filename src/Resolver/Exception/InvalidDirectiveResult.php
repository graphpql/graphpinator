<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Exception;

final class InvalidDirectiveResult extends \Graphpinator\Resolver\Exception\ResolverError
{
    public const MESSAGE = 'Directive callback must return DirectiveResult string.';
}
