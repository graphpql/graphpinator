<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class SelectionOnUnion extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Cannot require fields on union type, use fragments with concrete type conditions.';
}
