<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class SelectionOnLeaf extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Cannot require fields on leaf type.';
}
