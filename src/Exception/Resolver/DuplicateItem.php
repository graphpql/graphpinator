<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class DuplicateItem extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Duplicated item.';
}
