<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class SelectionOnComposite extends ResolverError
{
    public const MESSAGE = 'Composite type without fields specified.';
}
