<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class FragmentCycle extends NormalizerError
{
    public const MESSAGE = 'Fragment cycle detected.';
}
