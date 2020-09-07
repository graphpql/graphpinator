<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class ExpectedListOrNull extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Value must be list or null.';
}
