<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class SelectionOnLeaf extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Cannot require fields on leaf type.';
}
