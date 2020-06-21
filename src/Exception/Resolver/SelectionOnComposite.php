<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class SelectionOnComposite extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Composite type without fields specified.';
}
